<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitor_turma', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('monitor_id');
            $table->unsignedBigInteger('turma_id');
            $table->timestamps();
            $table->foreign('monitor_id')->references('id')->on('monitores')->onDelete('cascade');
            $table->foreign('turma_id')->references('id')->on('turmas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitor_turma');
    }
};
