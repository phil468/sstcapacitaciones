<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmpresaIdToCargosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('cargos') && !Schema::hasColumn('cargos', 'empresa_id')) {
            Schema::table('cargos', function (Blueprint $table) {
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
        if (Schema::hasTable('cargos') && Schema::hasColumn('cargos', 'empresa_id')) {
            Schema::table('cargos', function (Blueprint $table) {
                $table->dropForeign(['empresa_id']);
                $table->dropColumn('empresa_id');
            });
        }
    }
}
