<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Verifica se a coluna JÁ EXISTE antes de tentar adicionar
        if (!Schema::hasColumn('alunos', 'user_id')) {
            Schema::table('alunos', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->unique()->after('id');

                $table->foreign('user_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        // Verifica se a coluna EXISTE antes de tentar remover
        if (Schema::hasColumn('alunos', 'user_id')) {
            Schema::table('alunos', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }
    }
};