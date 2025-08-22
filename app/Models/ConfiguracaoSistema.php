<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfiguracaoSistema extends Model
{
    use HasFactory;

    protected $table = 'configuracao_sistema';

    protected $fillable = [
        'chave',
        'valor',
        'descricao',
        'tipo',
        'grupo'
    ];

    /**
     * Get configuration value by key.
     */
    public static function get($chave, $default = null)
    {
        $config = static::where('chave', $chave)->first();
        return $config ? $config->valor : $default;
    }

    /**
     * Set configuration value.
     */
    public static function set($chave, $valor, $descricao = null)
    {
        return static::updateOrCreate(
            ['chave' => $chave],
            [
                'valor' => $valor,
                'descricao' => $descricao
            ]
        );
    }

    /**
     * Get configurations by group.
     */
    public static function getByGroup($grupo)
    {
        return static::where('grupo', $grupo)->pluck('valor', 'chave');
    }
}
