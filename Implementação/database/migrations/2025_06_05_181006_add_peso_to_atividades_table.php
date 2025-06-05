<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('atividades', function (Blueprint $table) {
            $table->decimal('peso', 5, 2)->default(1)->after('descricao'); // ou onde você preferir
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('atividades', function (Blueprint $table) {
            $table->dropColumn('peso');
        });
    }
};
