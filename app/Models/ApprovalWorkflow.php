<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalWorkflow extends Model
{
    use HasFactory;

    protected $fillable = [
        'workflowable_type',
        'workflowable_id',
        'current_step',
        'steps',
        'current_user_id',
        'created_by',
        'comments',
        'step_deadline',
        'metadata'
    ];

    protected $casts = [
        'steps' => 'array',
        'step_deadline' => 'datetime',
        'metadata' => 'array',
    ];

    const STEPS = [
        'draft' => 'Rascunho',
        'review' => 'Em Revisão',
        'approval' => 'Aguardando Aprovação',
        'published' => 'Publicado',
        'rejected' => 'Rejeitado'
    ];

    /**
     * Relacionamento com o modelo sendo processado
     */
    public function workflowable()
    {
        return $this->morphTo();
    }

    /**
     * Usuário responsável pela etapa atual
     */
    public function currentUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'current_user_id');
    }

    /**
     * Usuário que criou o workflow
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Avança para a próxima etapa
     */
    public function advanceStep($nextStep, $userId, $comments = null)
    {
        $previousStep = $this->current_step;
        $steps = $this->steps ?? [];
        
        // Adicionar histórico da etapa
        $steps[] = [
            'step' => $previousStep,
            'user_id' => $userId,
            'comments' => $comments,
            'completed_at' => now()->toISOString(),
            'action' => 'completed'
        ];

        // Determinar próximo usuário responsável
        $nextUserId = $this->getNextResponsibleUser($nextStep);
        
        // Definir prazo baseado na etapa
        $deadline = $this->getStepDeadline($nextStep);

        $this->update([
            'current_step' => $nextStep,
            'steps' => $steps,
            'current_user_id' => $nextUserId,
            'comments' => null, // Limpar comentários da etapa anterior
            'step_deadline' => $deadline
        ]);

        // Registrar na auditoria
        AuditLog::logActivity($this, 'step_advanced', [
            'from_step' => $previousStep,
            'to_step' => $nextStep,
            'comments' => $comments
        ], null, ['workflow', 'step_change']);

        return $this;
    }

    /**
     * Rejeita o workflow
     */
    public function reject($userId, $comments)
    {
        $steps = $this->steps ?? [];
        
        $steps[] = [
            'step' => $this->current_step,
            'user_id' => $userId,
            'comments' => $comments,
            'completed_at' => now()->toISOString(),
            'action' => 'rejected'
        ];

        $this->update([
            'current_step' => 'rejected',
            'steps' => $steps,
            'current_user_id' => $this->created_by, // Volta para o criador
            'comments' => $comments,
            'step_deadline' => null
        ]);

        // Registrar na auditoria
        AuditLog::logActivity($this, 'rejected', null, [
            'comments' => $comments,
            'rejected_by' => $userId
        ], ['workflow', 'rejection']);

        return $this;
    }

    /**
     * Reinicia o workflow
     */
    public function restart($userId, $comments = null)
    {
        $steps = $this->steps ?? [];
        
        $steps[] = [
            'step' => $this->current_step,
            'user_id' => $userId,
            'comments' => $comments,
            'completed_at' => now()->toISOString(),
            'action' => 'restarted'
        ];

        $this->update([
            'current_step' => 'draft',
            'steps' => $steps,
            'current_user_id' => $this->created_by,
            'comments' => $comments,
            'step_deadline' => $this->getStepDeadline('draft')
        ]);

        // Registrar na auditoria
        AuditLog::logActivity($this, 'restarted', null, [
            'comments' => $comments,
            'restarted_by' => $userId
        ], ['workflow', 'restart']);

        return $this;
    }

    /**
     * Verifica se o usuário pode atuar na etapa atual
     */
    public function canUserAct($userId)
    {
        return $this->current_user_id === $userId || 
               $this->hasUserPermission($userId, $this->current_step);
    }

    /**
     * Verifica se o workflow está atrasado
     */
    public function isOverdue()
    {
        return $this->step_deadline && $this->step_deadline->isPast();
    }

    /**
     * Obtém o próximo usuário responsável baseado na etapa
     */
    private function getNextResponsibleUser($step)
    {
        // Lógica para determinar quem é responsável por cada etapa
        switch ($step) {
            case 'review':
                // Buscar usuário com permissão de revisor
                return User::where('can_review', true)->first()?->id;
                
            case 'approval':
                // Buscar usuário com permissão de aprovador
                return User::where('can_approve', true)->first()?->id;
                
            case 'published':
                // Buscar usuário com permissão de publicar
                return User::where('can_publish', true)->first()?->id;
                
            default:
                return $this->created_by;
        }
    }

    /**
     * Calcula prazo para a etapa
     */
    private function getStepDeadline($step)
    {
        $deadlines = [
            'draft' => 7, // 7 dias
            'review' => 3, // 3 dias
            'approval' => 2, // 2 dias
            'published' => 1, // 1 dia
        ];

        $days = $deadlines[$step] ?? 3;
        return now()->addDays($days);
    }

    /**
     * Verifica permissão do usuário para a etapa
     */
    private function hasUserPermission($userId, $step)
    {
        $user = User::find($userId);
        if (!$user) return false;

        switch ($step) {
            case 'review':
                return $user->can_review ?? false;
            case 'approval':
                return $user->can_approve ?? false;
            case 'published':
                return $user->can_publish ?? false;
            default:
                return true;
        }
    }

    /**
     * Scopes
     */
    public function scopePendingFor($query, $userId)
    {
        return $query->where('current_user_id', $userId)
                    ->whereNotIn('current_step', ['published', 'rejected']);
    }

    public function scopeOverdue($query)
    {
        return $query->whereNotNull('step_deadline')
                    ->where('step_deadline', '<', now())
                    ->whereNotIn('current_step', ['published', 'rejected']);
    }

    public function scopeByStep($query, $step)
    {
        return $query->where('current_step', $step);
    }

    /**
     * Atributos computados
     */
    public function getStepNameAttribute()
    {
        return self::STEPS[$this->current_step] ?? $this->current_step;
    }

    public function getIsOverdueAttribute()
    {
        return $this->isOverdue();
    }

    public function getDaysToDeadlineAttribute()
    {
        if (!$this->step_deadline) return null;
        return now()->diffInDays($this->step_deadline, false);
    }
}
