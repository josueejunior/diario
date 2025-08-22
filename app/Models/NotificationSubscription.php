<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NotificationSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'phone',
        'name',
        'type',
        'preferences',
        'active',
        'token',
        'verified_at',
        'last_notification_at'
    ];

    protected $casts = [
        'preferences' => 'array',
        'active' => 'boolean',
        'verified_at' => 'datetime',
        'last_notification_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($subscription) {
            if (!$subscription->token) {
                $subscription->token = Str::random(64);
            }
        });
    }

    /**
     * Scope para inscrições ativas
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope para inscrições verificadas
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    /**
     * Scope para filtrar por tipo de documento
     */
    public function scopeForDocumentType($query, $tipoId)
    {
        return $query->where(function ($q) use ($tipoId) {
            $q->whereNull('preferences')
              ->orWhereJsonContains('preferences->tipos', $tipoId)
              ->orWhereJsonLength('preferences->tipos', 0);
        });
    }

    /**
     * Scope para filtrar por órgão
     */
    public function scopeForOrgao($query, $orgaoId)
    {
        return $query->where(function ($q) use ($orgaoId) {
            $q->whereNull('preferences')
              ->orWhereJsonContains('preferences->orgaos', $orgaoId)
              ->orWhereJsonLength('preferences->orgaos', 0);
        });
    }

    /**
     * Scope para filtrar por palavras-chave
     */
    public function scopeForKeywords($query, $title, $content)
    {
        return $query->where(function ($q) use ($title, $content) {
            $q->whereNull('preferences')
              ->orWhere(function ($subQ) use ($title, $content) {
                  $subQ->whereRaw("JSON_EXTRACT(preferences, '$.keywords') IS NOT NULL")
                       ->where(function ($keywordQ) use ($title, $content) {
                           // Verificar se alguma palavra-chave está no título ou conteúdo
                           $keywordQ->whereRaw("LOWER(?) REGEXP LOWER(JSON_UNQUOTE(JSON_EXTRACT(preferences, '$.keywords[0]')))", [$title . ' ' . $content]);
                       });
              });
        });
    }

    /**
     * Verifica se deve notificar baseado nas preferências
     */
    public function shouldNotify($materia)
    {
        if (!$this->active || !$this->verified_at) {
            return false;
        }

        $preferences = $this->preferences ?? [];

        // Se não há preferências, notifica tudo
        if (empty($preferences)) {
            return true;
        }

        // Verificar tipo
        if (!empty($preferences['tipos']) && !in_array($materia->tipo_id, $preferences['tipos'])) {
            return false;
        }

        // Verificar órgão
        if (!empty($preferences['orgaos']) && !in_array($materia->orgao_id, $preferences['orgaos'])) {
            return false;
        }

        // Verificar palavras-chave
        if (!empty($preferences['keywords'])) {
            $text = strtolower($materia->titulo . ' ' . $materia->texto);
            $found = false;
            foreach ($preferences['keywords'] as $keyword) {
                if (strpos($text, strtolower($keyword)) !== false) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                return false;
            }
        }

        return true;
    }

    /**
     * Marca como verificado
     */
    public function markAsVerified()
    {
        $this->update(['verified_at' => now()]);
    }

    /**
     * Atualiza último envio de notificação
     */
    public function markNotificationSent()
    {
        $this->update(['last_notification_at' => now()]);
    }

    /**
     * Desativa a inscrição
     */
    public function unsubscribe()
    {
        $this->update(['active' => false]);
    }

    /**
     * URL para cancelar inscrição
     */
    public function getUnsubscribeUrlAttribute()
    {
        return route('notifications.unsubscribe', $this->token);
    }

    /**
     * URL para verificar email
     */
    public function getVerifyUrlAttribute()
    {
        return route('notifications.verify', $this->token);
    }
}
