<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alunos', function (Blueprint $table) {
            Schema::table('alunos', function (Blueprint $table) {
                $table->renameColumn('nome_escola', 'escola_id'); // Renomeia a coluna
                $table->renameColumn('nome_turma', 'turma_id');   // Renomeia a coluna
            });
        });
    }
    public function down(): void
    {
        Schema::table('alunos', function (Blueprint $table) {
            Schema::table('alunos', function (Blueprint $table) {
                $table->renameColumn('escola_id', 'nome_escola'); // Reverte para o nome original
                $table->renameColumn('turma_id', 'nome_turma');   // Reverte para o nome original
            });
        });
    }
};
