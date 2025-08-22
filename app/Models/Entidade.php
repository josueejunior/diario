<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entidade extends Model
{
    use HasFactory;

    protected $table = 'entidade';

    protected $fillable = [
        'nome',
        'nome_fantasia',
        'cnpj',
        'inscricao_estadual',
        'inscricao_municipal',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'cep',
        'telefone',
        'email',
        'site',
        'logo'
    ];

    public function getEnderecoCompletoAttribute()
    {
        $endereco = $this->endereco . ', ' . $this->numero;
        if ($this->complemento) {
            $endereco .= ', ' . $this->complemento;
        }
        $endereco .= ' - ' . $this->bairro;
        $endereco .= ', ' . $this->cidade . '/' . $this->estado;
        $endereco .= ' - CEP: ' . $this->cep;
        
        return $endereco;
    }

    public function getCnpjFormatadoAttribute()
    {
        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $this->cnpj);
    }

    public function getCepFormatadoAttribute()
    {
        return preg_replace('/(\d{5})(\d{3})/', '$1-$2', $this->cep);
    }

    public static function principal()
    {
        return static::first();
    }
}
