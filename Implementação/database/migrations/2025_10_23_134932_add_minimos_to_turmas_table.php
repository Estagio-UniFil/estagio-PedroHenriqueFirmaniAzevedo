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
        Schema::table('turmas', function (Blueprint $table) {
            // Adiciona as colunas se elas não existirem
            if (!Schema::hasColumn('turmas', 'minimo_presenca')) {
                $table->integer('minimo_presenca')->default(75)->after('laboratorio');
            }
            if (!Schema::hasColumn('turmas', 'minimo_nota')) {
                $table->integer('minimo_nota')->default(60)->after('minimo_presenca');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('turmas', function (Blueprint $table) {
            // Remove as colunas se elas existirem (para poder reverter)
            if (Schema::hasColumn('turmas', 'minimo_presenca')) {
                $table->dropColumn('minimo_presenca');
            }
            if (Schema::hasColumn('turmas', 'minimo_nota')) {
                $table->dropColumn('minimo_nota');
            }
        });
    }
};