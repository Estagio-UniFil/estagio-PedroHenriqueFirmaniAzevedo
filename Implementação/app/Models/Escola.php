<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Escola extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'escolas';

    protected $fillable = [
        'nome_escola',
        'responsavel_escola',
        'contato'
    ];
}
