<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmpresaIdToTipoDePuestosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('tipo_de_puestos') && !Schema::hasColumn('tipo_de_puestos', 'empresa_id')) {
            Schema::table('tipo_de_puestos', function (Blueprint $table) {
                $table->unsignedBigInteger('empresa_id')->nullable()->after('name');
                $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('tipo_de_puestos') && Schema::hasColumn('tipo_de_puestos', 'empresa_id')) {
            Schema::table('tipo_de_puestos', function (Blueprint $table) {
                $table->dropForeign(['empresa_id']);
                $table->dropColumn('empresa_id');
            });
        }
    }
}
