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
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade'); // Servicio al que pertenece la disponibilidad
            $table->date('availability_date'); // Fecha de disponibilidad
            $table->time('start_time'); // Hora de inicio del turno
            $table->time('end_time'); // Hora de finalización del turno
            $table->boolean('is_available')->default(true); // Estado de disponibilidad
            $table->timestamps();

            // Índice único para evitar duplicados en el mismo servicio, fecha y hora
            $table->unique(['service_id', 'availability_date', 'start_time']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('availabilities');
    }
};
