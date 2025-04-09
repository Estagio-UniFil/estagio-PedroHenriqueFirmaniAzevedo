<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('alunos', function (Blueprint $table) {
            $table->id();
            $table->string('nome_aluno');
            $table->unsignedBigInteger('nome_escola');
            $table->foreign('nome_escola')->references('id')->on('escolas')->onDelete('cascade');
            
            $table->unsignedBigInteger('nome_turma'); // Deve ser unsignedBigInteger

            $table->foreign('nome_turma')->references('id')->on('turmas')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alunos');
    }
};
