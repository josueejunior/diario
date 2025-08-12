<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Edicao extends Model
{
    use HasFactory;
    
    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'edicoes';
    
    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'numero', 
        'data',
        'data_publicacao', 
        'tipo',
        'publicado', 
        'hash', 
        'caminho_arquivo',
        'carimbo_tempo',
        'signatario',
        'ac',
        'algoritmo',
        'tamanho',
        'visualizacoes',
        'downloads',
        'descricao'
    ];
    
    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'date',
        'data_publicacao' => 'datetime',
        'carimbo_tempo' => 'datetime',
        'publicado' => 'boolean',
        'visualizacoes' => 'integer',
        'downloads' => 'integer',
        'tamanho' => 'integer',
    ];
    
    /**
     * Obter as matérias associadas a esta edição.
     */
    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'edicao_materia')->withTimestamps();
    }
    
    /**
     * Obter as assinaturas associadas a esta edição.
     */
    public function assinaturas()
    {
        return $this->hasMany(Assinatura::class);
    }
    
    /**
     * Obter a primeira assinatura associada a esta edição (para compatibilidade).
     */
    public function assinatura()
    {
        return $this->hasOne(Assinatura::class);
    }
    
    /**
     * Obter os downloads associados a esta edição.
     */
    public function downloads()
    {
        return $this->hasMany(Download::class);
    }
    
    /**
     * Obter as visualizações associadas a esta edição.
     */
    public function visualizacoes()
    {
        return $this->hasMany(Visualizacao::class);
    }
}
