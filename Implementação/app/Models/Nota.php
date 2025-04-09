<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    use HasFactory;

    protected $fillable = [
        'atividade_id',
        'aluno_id', 
        'nota'
    ];

    public function atividade()
    {
        return $this->belongsTo(Atividade::class);
    }

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }
}   
