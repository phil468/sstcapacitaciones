<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVisibleToCapacitacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('capacitaciones', function (Blueprint $table) {
            $table->boolean('visible')->default(true)->after('es_aula_virtual');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('capacitaciones', function (Blueprint $table) {
            $table->dropColumn('visible');
        });
    }
}
