<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->string('availability_date'); // Puede ser un string con los días: Lunes, Martes, etc.
            $table->string('start_time');
            $table->string('end_time');
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }
    

    public function down()
    {
        Schema::dropIfExists('availabilities');
    }
};
