<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visualizacao extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'visualizacoes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'edicao_id',
        'ip',
        'user_agent',
        'origem'
    ];

    /**
     * Get the edicao associated with the visualizacao.
     */
    public function edicao()
    {
        return $this->belongsTo(Edicao::class);
    }
}
