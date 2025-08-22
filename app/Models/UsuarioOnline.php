<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioOnline extends Model
{
    use HasFactory;

    protected $table = 'usuarios_online';

    protected $fillable = [
        'session_id',
        'user_id',
        'ip_address',
        'user_agent',
        'pagina_atual',
        'ultimo_acesso',
        'tempo_sessao'
    ];

    protected $casts = [
        'ultimo_acesso' => 'datetime',
        'tempo_sessao' => 'integer'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeAtivos($query, $minutos = 5)
    {
        return $query->where('ultimo_acesso', '>=', now()->subMinutes($minutos));
    }

    public function scopeLogados($query)
    {
        return $query->whereNotNull('user_id');
    }

    public function scopeAnonimos($query)
    {
        return $query->whereNull('user_id');
    }

    public function getTempoSessaoHumanAttribute()
    {
        $segundos = $this->tempo_sessao;
        $horas = floor($segundos / 3600);
        $minutos = floor(($segundos % 3600) / 60);
        $segs = $segundos % 60;

        return sprintf('%02d:%02d:%02d', $horas, $minutos, $segs);
    }

    public static function contarOnline($minutos = 5)
    {
        return static::where('ultimo_acesso', '>=', now()->subMinutes($minutos))->count();
    }
}
