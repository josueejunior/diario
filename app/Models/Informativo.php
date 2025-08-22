<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Informativo extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'conteudo',
        'data_inicio',
        'data_fim',
        'tipo',
        'status',
        'destaque',
        'imagem',
        'autor_id'
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'destaque' => 'boolean'
    ];

    public function autor()
    {
        return $this->belongsTo(User::class, 'autor_id');
    }

    public function scopeAtivos($query)
    {
        return $query->where('status', 'ativo');
    }

    public function scopeDestaque($query)
    {
        return $query->where('destaque', true);
    }

    public function isVigente()
    {
        $hoje = now()->format('Y-m-d');
        return $this->data_inicio <= $hoje && 
               ($this->data_fim === null || $this->data_fim >= $hoje);
    }
}
