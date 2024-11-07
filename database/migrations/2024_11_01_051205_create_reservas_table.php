<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Usuario que realiza la reserva
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade'); // Servicio reservado
            $table->date('reservation_date'); // Fecha de la reserva
            $table->time('start_time'); // Hora de inicio de la reserva
            $table->boolean('is_notified')->default(false);
            $table->timestamp('notified_at')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservas');
    }
};
