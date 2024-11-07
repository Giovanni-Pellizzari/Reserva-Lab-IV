@extends('layouts.app')

@section('content')
    <div class="container text-center">
        <h1>Servicios Disponibles</h1>

        <div class="row">
            @if ($services->isEmpty())
                <p>No hay servicios disponibles.</p>
            @else
                @foreach ($services as $service)
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="{{ asset('storage/' . $service->image) }}" class="card-img-top" alt="{{ $service->name }}" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title">{{ $service->name }}</h5>
                                <button class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#serviceModal{{ $service->id }}">
                                    Ver Descripción
                                </button>
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#reservationModal{{ $service->id }}">
                                    Reservar
                                </button>
                            </div>
                        </div>

                        <!-- Modal para la Descripción -->
                        <div class="modal fade" id="serviceModal{{ $service->id }}" tabindex="-1" aria-labelledby="serviceModalLabel{{ $service->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="serviceModalLabel{{ $service->id }}">{{ $service->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>{{ $service->description }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
<!-- Modal para Reservar -->
<div class="modal fade" id="reservationModal{{ $service->id }}" tabindex="-1" aria-labelledby="reservationModalLabel{{ $service->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reservationModalLabel{{ $service->id }}">Reservar {{ $service->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('reservas.store') }}">
                @csrf
                <input type="hidden" name="service_id" value="{{ $service->id }}">

                <div class="modal-body">
                <div class="form-group mt-3">
    <label for="reservation_date">Fecha de Reserva</label>
    <input type="date" class="form-control" id="reservation_date" name="reservation_date" required>
</div>

<div class="form-group mt-3">
    <label for="reservation_time">Hora de Reserva</label>
    <select class="form-control" id="reservation_time" name="reservation_time" required>
        <option value="">Seleccione una hora</option>
    </select>
</div>

                
                                            <!-- Precio del Servicio (funciona) -->
                                            <div class="form-group mt-3">
                                                <label>Precio del Servicio: </label>
                                                <p>${{ number_format($service->price, 2) }}</p>
                                            </div>

                                            <!-- Método de Pago -->
                                            <div class="form-group mt-3">
                                                <label for="payment_method">Método de Pago</label>
                                                <select class="form-control" id="payment_method" name="payment_method" required>
                                                    <option value="">Selecciona un método de pago</option>
                                                    <option value="credit_card">Tarjeta de Crédito</option>
                                                    <option value="debit_card">Tarjeta de Débito</option>
                                                </select>
                                            </div>

                                            <!-- Información de Tarjeta (se muestra si se selecciona un método de pago pero faltan detalles) -->
                                            <div id="paymentDetails" class="mt-3" style="display: none;">
                                                <div class="form-group">
                                                    <label for="card_number">Número de Tarjeta</label>
                                                    <input type="text" class="form-control" id="card_number" name="card_number" pattern="\d{16}" placeholder="0000 0000 0000 0000" required>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <label for="expiry_date">Fecha de Expiración</label>
                                                    <input type="month" class="form-control" id="expiry_date" name="expiry_date" required>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <label for="cvv">CVV</label>
                                                    <input type="text" class="form-control" id="cvv" name="cvv" pattern="\d{3}" placeholder="123" required>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-primary">Reservar y Pagar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div> 

    <script>
        // Mostrar detalles de pago solo cuando se selecciona un método de tarjeta 
        document.querySelectorAll('[id^="payment_method"]').forEach(function (selectElement) {
            selectElement.addEventListener('change', function () {
                const paymentDetails = this.closest('.modal-body').querySelector('#paymentDetails');
                if (this.value === 'credit_card' || this.value === 'debit_card') {
                    paymentDetails.style.display = 'block';
                } else {
                    paymentDetails.style.display = 'none';
                }
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
    $(document).ready(function() {
        // Event listener for date and service selection within each modal
        $('[id^="reservation_date"]').on('change', function() {
            const modal = $(this).closest('.modal'); // Locate the specific modal
            const serviceId = modal.find('input[name="service_id"]').val();
            const reservationDate = $(this).val();

            if (serviceId && reservationDate) {
                // AJAX request to get available slots for selected service and date
                $.ajax({
                    url: "{{ route('reservas.available-slots') }}",
                    type: 'GET',
                    data: { service_id: serviceId, reservation_date: reservationDate },
                    success: function(slots) {
                        const timeSelect = modal.find('#reservation_time');
                        timeSelect.empty(); // Clear previous options

                        if (slots.length > 0) {
                            slots.forEach(function(slot) {
                                timeSelect.append(`<option value="${slot}">${slot}</option>`);
                            });
                        } else {
                            timeSelect.append('<option value="">No hay horarios disponibles</option>');
                        }
                    },
                    error: function() {
                        const timeSelect = modal.find('#reservation_time');
                        timeSelect.empty();
                        timeSelect.append('<option value="">Error al cargar horarios</option>');
                    }
                });
            } else {
                const timeSelect = modal.find('#reservation_time');
                timeSelect.empty();
                timeSelect.append('<option value="">Seleccione un servicio y una fecha</option>');
            }
        });
    });
</script>

    
    <style>
        body {
        background-color: rgb(72, 61, 139); 
        }

        .container {
            margin-top: 20px;
        }

        h1 {
        color: white; 
        margin-top: 20px;
        margin-bottom: 50px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s;
            border: 2px solid rgb(72, 61, 139);
            background-color: #0d0d0d;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card-img-top {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .card-body {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .btn {
            width: 100%;
            margin-bottom: 10px; 
        }

        .modal-content {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            border-bottom: 2px solid #007bff;
        }

        .modal-footer {
            border-top: 2px solid #007bff;
        }
    </style>


@endsection
