<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descricao',
        'prioridade',
        'status',
        'categoria',
        'usuario_id',
        'atribuido_para',
        'data_abertura',
        'data_fechamento'
    ];

    protected $casts = [
        'data_abertura' => 'datetime',
        'data_fechamento' => 'datetime'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function atribuido()
    {
        return $this->belongsTo(User::class, 'atribuido_para');
    }

    public function respostas()
    {
        return $this->hasMany(TicketResposta::class);
    }

    public function scopeAbertos($query)
    {
        return $query->whereIn('status', ['aberto', 'em_andamento', 'aguardando']);
    }

    public function scopePorPrioridade($query, $prioridade)
    {
        return $query->where('prioridade', $prioridade);
    }

    public function scopeMeus($query, $userId)
    {
        return $query->where('usuario_id', $userId);
    }

    public function scopeAtribuidosPara($query, $userId)
    {
        return $query->where('atribuido_para', $userId);
    }

    public function isAberto()
    {
        return in_array($this->status, ['aberto', 'em_andamento', 'aguardando']);
    }

    public function getPrioridadeCorAttribute()
    {
        return [
            'baixa' => 'success',
            'media' => 'warning', 
            'alta' => 'danger',
            'critica' => 'dark'
        ][$this->prioridade] ?? 'secondary';
    }
}
