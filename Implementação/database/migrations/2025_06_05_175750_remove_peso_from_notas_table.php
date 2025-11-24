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
        Schema::table('notas', function (Blueprint $table) {
            // Verifica se a coluna 'peso' existe antes de tentar removê-la
            if (Schema::hasColumn('notas', 'peso')) {
                $table->dropColumn('peso');
            }
        });
    }

public function down()
{
    Schema::table('notas', function (Blueprint $table) {
        $table->float('peso')->default(1); // ou nullable
    });
}
};
