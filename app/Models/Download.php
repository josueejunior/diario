<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    use HasFactory;

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
     * Get the edicao associated with the download.
     */
    public function edicao()
    {
        return $this->belongsTo(Edicao::class);
    }
}
