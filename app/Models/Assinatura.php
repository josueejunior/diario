<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assinatura extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'edicao_id',
        'signatario',
        'ac',
        'algoritmo',
        'hash',
        'carimbo_tempo',
        'cadeia_certificados',
        'signed_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'carimbo_tempo' => 'datetime',
        'cadeia_certificados' => 'array'
    ];

    /**
     * Get the edicao that owns the assinatura.
     */
    public function edicao()
    {
        return $this->belongsTo(Edicao::class);
    }

    /**
     * Get the user that signed the edicao.
     */
    public function signer()
    {
        return $this->belongsTo(User::class, 'signed_by');
    }
}
