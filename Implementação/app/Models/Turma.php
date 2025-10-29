<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Turma extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nome_turma',
        'instrutor_responsavel',
        'nivel',
        'horario',
        'dia_semana',
        'quantidade_alunos',
        'laboratorio',
        'minimo_presenca',
        'minimo_nota',
    ];

    protected $dates = ['deleted_at'];

    public function alunos()
    {
        return $this->hasMany(Aluno::class);
    }

    public function atividades()
    {
        return $this->hasMany(Atividade::class);
    }
    
    public function monitores()
    {
        return $this->belongsToMany(Monitor::class, 'monitor_turma');
    }
}
