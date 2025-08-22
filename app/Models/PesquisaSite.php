<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesquisaSite extends Model
{
    use HasFactory;

    protected $table = 'pesquisas_site';

    protected $fillable = [
        'termo_pesquisa',
        'resultados_encontrados',
        'ip_usuario',
        'user_agent',
        'referrer',
        'filtros_aplicados',
        'data_pesquisa'
    ];

    protected $casts = [
        'filtros_aplicados' => 'array',
        'data_pesquisa' => 'datetime',
        'resultados_encontrados' => 'integer'
    ];

    public function scopePorPeriodo($query, $inicio, $fim)
    {
        return $query->whereBetween('data_pesquisa', [$inicio, $fim]);
    }

    public function scopeComResultados($query)
    {
        return $query->where('resultados_encontrados', '>', 0);
    }

    public function scopeSemResultados($query)
    {
        return $query->where('resultados_encontrados', 0);
    }

    public static function termosPopulares($limite = 10)
    {
        return static::selectRaw('termo_pesquisa, COUNT(*) as total')
            ->groupBy('termo_pesquisa')
            ->orderByDesc('total')
            ->limit($limite)
            ->get();
    }
}
