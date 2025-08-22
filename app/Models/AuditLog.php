<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'auditable_type',
        'auditable_id',
        'event',
        'old_values',
        'new_values',
        'user_id',
        'ip_address',
        'user_agent',
        'url',
        'tags'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'tags' => 'array',
    ];

    /**
     * Relacionamento com o usuário que executou a ação
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtém o modelo auditado
     */
    public function auditable()
    {
        return $this->morphTo();
    }

    /**
     * Scope para filtrar por tipo de evento
     */
    public function scopeByEvent($query, $event)
    {
        return $query->where('event', $event);
    }

    /**
     * Scope para filtrar por modelo
     */
    public function scopeByModel($query, $modelType)
    {
        return $query->where('auditable_type', $modelType);
    }

    /**
     * Scope para filtrar por usuário
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para filtrar por período
     */
    public function scopeByPeriod($query, $startDate, $endDate = null)
    {
        $query->whereDate('created_at', '>=', $startDate);
        
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        
        return $query;
    }

    /**
     * Scope para buscar por tags
     */
    public function scopeByTag($query, $tag)
    {
        return $query->whereJsonContains('tags', $tag);
    }

    /**
     * Obtém uma descrição amigável da ação
     */
    public function getActionDescriptionAttribute()
    {
        $modelName = class_basename($this->auditable_type);
        $userName = $this->user ? $this->user->name : 'Sistema';
        
        $actions = [
            'created' => "criou {$modelName}",
            'updated' => "atualizou {$modelName}",
            'deleted' => "excluiu {$modelName}",
            'published' => "publicou {$modelName}",
            'signed' => "assinou {$modelName}",
            'approved' => "aprovou {$modelName}",
            'rejected' => "rejeitou {$modelName}",
            'viewed' => "visualizou {$modelName}",
            'downloaded' => "baixou {$modelName}",
        ];
        
        $action = $actions[$this->event] ?? $this->event;
        
        return "{$userName} {$action}";
    }

    /**
     * Obtém as mudanças de forma amigável
     */
    public function getChangesAttribute()
    {
        if (!$this->old_values || !$this->new_values) {
            return [];
        }

        $changes = [];
        
        foreach ($this->new_values as $field => $newValue) {
            $oldValue = $this->old_values[$field] ?? null;
            
            if ($oldValue !== $newValue) {
                $changes[$field] = [
                    'old' => $oldValue,
                    'new' => $newValue
                ];
            }
        }
        
        return $changes;
    }

    /**
     * Cria um log de auditoria
     */
    public static function logActivity($model, $event, $oldValues = null, $newValues = null, $tags = [])
    {
        if (!auth()->check() && !app()->runningInConsole()) {
            return;
        }

        $user = auth()->user();
        $request = request();

        static::create([
            'auditable_type' => get_class($model),
            'auditable_id' => $model->id,
            'event' => $event,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'user_id' => $user ? $user->id : null,
            'ip_address' => $request ? $request->ip() : null,
            'user_agent' => $request ? $request->userAgent() : null,
            'url' => $request ? $request->fullUrl() : null,
            'tags' => $tags
        ]);
    }
}
