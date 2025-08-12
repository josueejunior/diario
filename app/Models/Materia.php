<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipo_id',
        'orgao_id',
        'numero',
        'data',
        'titulo',
        'texto',
        'arquivo',
        'status',
        'notas_revisao'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'date',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the tipo that owns the materia.
     */
    public function tipo()
    {
        return $this->belongsTo(Tipo::class);
    }

    /**
     * Get the orgao that owns the materia.
     */
    public function orgao()
    {
        return $this->belongsTo(Orgao::class);
    }

    /**
     * Get the edicoes that contain this materia.
     */
    public function edicoes()
    {
        return $this->belongsToMany(Edicao::class, 'edicao_materia')
            ->withTimestamps();
    }

    /**
     * Get the user that created the materia.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user that approved the materia.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope a query to only include materias with a specific status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    
    /**
     * Get the visualizacoes for the materia.
     */
    public function visualizacoes()
    {
        return $this->hasMany(Visualizacao::class);
    }
    
    /**
     * Get the first edicao that contains this materia.
     * This is an accessor method, not a relationship
     * 
     * @return \App\Models\Edicao|null
     */
    public function getEdicaoAttribute()
    {
        return $this->edicoes()->latest()->first();
    }
}
