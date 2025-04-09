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
        Schema::create('presencas', function (Blueprint $table) {
            $table->id();
            $table->date('data');
            $table->unsignedBigInteger('turma_id');
            $table->foreign('turma_id')->references('id')->on('turmas')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('presenca_alunos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('presenca_id');
            $table->unsignedBigInteger('aluno_id');
            $table->string('presente')->default('0');
            $table->text('observacao')->nullable();
            $table->foreign('presenca_id')->references('id')->on('presencas')->onDelete('cascade');
            $table->foreign('aluno_id')->references('id')->on('alunos')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('presenca_monitores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('presenca_id');
            $table->unsignedBigInteger('monitor_id');
            $table->string('presente')->default('0');
            $table->text('observacao')->nullable();
            $table->foreign('presenca_id')->references('id')->on('presencas')->onDelete('cascade');
            $table->foreign('monitor_id')->references('id')->on('monitores')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presencas');
        Schema::dropIfExists('presenca_alunos');
        Schema::dropIfExists('presenca_monitores');
    }
};
