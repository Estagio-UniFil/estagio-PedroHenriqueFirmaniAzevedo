<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Atividade extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'titulo', 
        'descricao', 
        'turma_id',
        'peso'
    ];

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function notas()
    {
        return $this->hasMany(Nota::class);
    }
    
    public function presenca()
    {
        return $this->hasOneThrough(Presenca::class, Turma::class, 'id', 'turma_id', 'turma_id', 'id');
    }
}
