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
        Schema::table('alunos', function (Blueprint $table) {
             // Torna a coluna user_id nullable e unique
             // O ->change() requer o pacote doctrine/dbal: composer require doctrine/dbal
             $table->unsignedBigInteger('user_id')->nullable()->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alunos', function (Blueprint $table) {
            // Reverte as alterações: remove unique e nullability (ajuste conforme necessário)
            // É importante remover o índice unique primeiro
             $table->dropUnique(['user_id']); // Nome do índice padrão: alunos_user_id_unique
             // Para reverter o nullable, você precisaria decidir um valor padrão ou
             // garantir que não haja nulos antes de tornar não nulo novamente.
             // A reversão simples pode ser complexa dependendo do estado do banco.
             // Exemplo: $table->unsignedBigInteger('user_id')->nullable(false)->change(); // Pode falhar se houver nulos
        });
    }
};