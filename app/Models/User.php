<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cpf',
        'cargo',
        'ac_certificado',
        'pode_assinar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'pode_assinar' => 'boolean',
    ];

    /**
     * Verificar se o usuário pode assinar documentos
     */
    public function podeAssinar()
    {
        return $this->pode_assinar && !empty($this->cpf) && !empty($this->cargo);
    }

    /**
     * Obter o CPF formatado
     */
    public function getCpfFormatadoAttribute()
    {
        if (!$this->cpf) return null;
        
        return substr($this->cpf, 0, 3) . '.' . 
               substr($this->cpf, 3, 3) . '.' . 
               substr($this->cpf, 6, 3) . '-' . 
               substr($this->cpf, 9, 2);
    }

    /**
     * Obter identificador completo para assinatura
     */
    public function getIdentificadorAssinaturaAttribute()
    {
        return strtoupper($this->name) . ':' . $this->cpf;
    }
}
