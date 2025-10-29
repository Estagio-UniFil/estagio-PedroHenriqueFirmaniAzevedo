<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aluno extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nome_aluno',
        'escola_id',
        'turma_id',
        'user_id',
    ];

    public function turma()
    {
        return $this->belongsTo(Turma::class, 'turma_id');
    }

    public function escola()
    {
        return $this->belongsTo(Escola::class, 'escola_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notas()
    {
        return $this->hasMany(Nota::class);
    }
}
