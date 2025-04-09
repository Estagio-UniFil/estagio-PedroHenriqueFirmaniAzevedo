<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presenca extends Model
{
    use HasFactory;

    protected $fillable = [
        'data',
        'turma_id',
    ];

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function alunos()
    {
        return $this->hasMany(PresencaAluno::class);
    }

    public function monitores()
    {
        return $this->hasMany(PresencaMonitor::class);
    }
}

