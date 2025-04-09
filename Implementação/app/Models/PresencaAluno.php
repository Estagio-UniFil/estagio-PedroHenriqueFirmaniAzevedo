<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresencaAluno extends Model
{
    use HasFactory;

    protected $table = 'presenca_alunos';
    protected $fillable = ['presenca_id', 'aluno_id', 'presente', 'observacao'];

    public function presenca()
    {
        return $this->belongsTo(Presenca::class);
    }
}
