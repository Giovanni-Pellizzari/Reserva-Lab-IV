<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // En la migración generada
public function up()
{
    Schema::table('availabilities', function (Blueprint $table) {
        $table->date('availability_date')->change();  // Cambiar el tipo a 'date'
    });
}

public function down()
{
    Schema::table('availabilities', function (Blueprint $table) {
        $table->string('availability_date')->change();  // Regresar al tipo 'string' en caso de reversión
    });
}
};

