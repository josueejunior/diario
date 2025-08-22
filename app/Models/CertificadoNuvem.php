<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class CertificadoNuvem extends Model
{
    use HasFactory;

    protected $table = 'certificados_nuvem';

    protected $fillable = [
        'nome',
        'arquivo_p12',
        'senha_encrypted',
        'titular',
        'cpf_cnpj',
        'data_inicio',
        'data_vencimento',
        'tipo',
        'status',
        'observacoes'
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_vencimento' => 'date'
    ];

    protected $hidden = [
        'senha_encrypted'
    ];

    public function setSenhaAttribute($value)
    {
        $this->attributes['senha_encrypted'] = Crypt::encryptString($value);
    }

    public function getSenhaAttribute()
    {
        return Crypt::decryptString($this->senha_encrypted);
    }

    public function isVencido()
    {
        return $this->data_vencimento < now()->format('Y-m-d');
    }

    public function diasParaVencimento()
    {
        return now()->diffInDays($this->data_vencimento, false);
    }

    public function scopeAtivos($query)
    {
        return $query->where('status', 'ativo');
    }

    public function scopeVencendoEm($query, $dias = 30)
    {
        return $query->where('data_vencimento', '<=', now()->addDays($dias));
    }
}
