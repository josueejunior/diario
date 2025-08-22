<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoArquivo extends Model
{
    use HasFactory;

    protected $table = 'tipos_arquivos';

    protected $fillable = [
        'nome',
        'extensao',
        'mime_type',
        'tamanho_maximo',
        'descricao',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'tamanho_maximo' => 'integer'
    ];

    public function getTamanhoMaximoHumanAttribute()
    {
        return $this->formatBytes($this->tamanho_maximo);
    }

    private function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = ['B', 'KB', 'MB', 'GB', 'TB'];
        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }
}
