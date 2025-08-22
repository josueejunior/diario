<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    use HasFactory;
    
    public $timestamps = false; // Desabilita updated_at automático
    protected $dates = ['created_at'];

    protected $fillable = [
        'user_id',
        'action',
        'module',
        'resource_type',
        'resource_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'url',
        'method',
        'response_code',
        'created_at'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime'
    ];

    /**
     * Relacionamento com usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para filtrar por ação
     */
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope para logs de hoje
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope para logs de erro
     */
    public function scopeErrors($query)
    {
        return $query->where('action', 'error');
    }

    /**
     * Get formatted data attribute
     */
    public function getFormattedDataAttribute()
    {
        if (!$this->new_values) {
            return null;
        }
        
        return json_encode($this->new_values, JSON_PRETTY_PRINT);
    }

    /**
     * Get action badge class
     */
    public function getActionBadgeAttribute()
    {
        $badges = [
            'create' => 'badge-success',
            'update' => 'badge-info',
            'delete' => 'badge-danger',
            'publish' => 'badge-warning',
            'approve' => 'badge-primary',
            'sign' => 'badge-warning',
            'export' => 'badge-secondary',
            'download' => 'badge-light'
        ];

        return $badges[$this->action] ?? 'badge-light';
    }
}
