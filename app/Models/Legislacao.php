<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Legislacao extends Model
{
    use HasFactory;

    protected $table = 'legislacao';

    protected $fillable = [
        'titulo',
        'conteudo',
        'tipo',
        'numero',
        'data_publicacao',
        'ementa',
        'texto_completo',
        'arquivo_pdf',
        'status',
        'orgao_id'
    ];

    protected $casts = [
        'data_publicacao' => 'date'
    ];

    public function orgao()
    {
        return $this->belongsTo(Orgao::class);
    }

    public function scopeVigentes($query)
    {
        return $query->where('status', 'vigente');
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function getNumeroCompletoAttribute()
    {
        return ucfirst($this->tipo) . ' nº ' . $this->numero;
    }
}
