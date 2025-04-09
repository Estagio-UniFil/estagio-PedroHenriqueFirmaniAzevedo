<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Monitor extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'monitores';
    protected $fillable = [
        'nome_monitor',
        'email_monitor',
        'matricula',
        'tipo',
        'turmas => array',
    ];

    public function turmas()
    {
        return $this->belongsToMany(Turma::class, 'monitor_turma');
    }
}
