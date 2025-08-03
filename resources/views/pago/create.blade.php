@extends('layouts.app')

@section('content')
<style>
    .custom-card {
        max-width: 900px;
        background-color: #e6f0ff;
        border: 1px solid #91cfff;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2);
        margin: 2rem auto;
        padding: 2rem;
    }

    .custom-card h2 {
        text-align: center;
        margin-bottom: 1.5rem;
        color: #004a99;
    }

    label {
        font-weight: 600;
        display: block;
        margin-bottom: 0.5rem;
        color: #003366;
    }

    input[type="text"],
    input[type="number"],
    input[type="date"],
    input[type="tel"],
    select {
        width: 100%;
        padding: 0.6rem;
        margin-bottom: 1rem;
        border: 1px solid #b3d9ff;
        border-radius: 8px;
        background-color: #ffffff;
    }

    .grid-2-cols {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .submit-btn {
        background-color: #007bff;
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 10px;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .submit-btn:hover {
        background-color: #0056b3;
    }

    .hidden {
        display: none;
    }

    .error-text {
        color: red;
        font-size: 0.875rem;
    }

    .info-message {
        background-color: #d1ecf1;
        border: 1px solid #bee5eb;
        color: #0c5460;
        padding: 1rem;
        border-radius: 8px;
        margin-top: 1rem;
    }

    .logo-container {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .logo-container img {
        width: 100px;
        height: auto;
    }
</style>

<div class="custom-card">
    <div class="logo-container">
        <img src="{{ asset('images/barra.png') }}" alt="Logo">
    </div>

    <h2>Nuevo Pago</h2>
    <form action="{{ route('pago.store') }}" method="POST">
        @csrf

        {{-- Método de pago --}}
        <div style="margin-bottom: 1.5rem;">
            <label for="metodo_pago">Método de Pago</label>
            <select name="metodo_pago" id="metodo_pago" required onchange="mostrarCamposPago()" data-old="{{ old('metodo_pago') }}">
                <option value="">-- Selecciona un método --</option>
                <option value="tarjeta" {{ old('metodo_pago') == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                <option value="efectivo" {{ old('metodo_pago') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
            </select>

            @error('metodo_pago')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        {{-- Campos de tarjeta --}}
        <div id="tarjetaCampos" class="{{ old('metodo_pago') == 'tarjeta' ? '' : 'hidden' }}">
            <div class="grid-2-cols">
                <div>
                    <label for="nombre_titular">Nombre del Titular</label>
                    <input type="text" name="nombre_titular" maxlength="50" autocomplete="off" placeholder="Nombre en la tarjeta" value="{{ old('nombre_titular') }}">
                    @error('nombre_titular')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="numero_tarjeta">Número de Tarjeta</label>
                    <input type="text" name="numero_tarjeta" maxlength="19" autocomplete="off" placeholder="0000 0000 0000 0000" maxlength="19" value="{{ old('numero_tarjeta') }}">
                    @error('numero_tarjeta')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="grid-2-cols">
                <div>
                    <label for="fecha_expiracion">Fecha de Expiración</label>
                    <input type="month" name="fecha_expiracion" value="{{ old('fecha_expiracion') }}">
                    @error('fecha_expiracion')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="cvv">CVV</label>
                    <input type="text" name="cvv" maxlength="4" autocomplete="off" placeholder="123" maxlength="4" value="{{ old('cvv') }}">
                    @error('cvv')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Servicio y descripción breve dentro de tarjeta --}}
            <div class="grid-2-cols">
                <div>
                    <label for="servicio">Servicio</label>
                    <select name="servicio" id="servicio">
                        <option value="">-- Selecciona un servicio --</option>
                        <option value="consulta_medica" {{ old('servicio') == 'consulta_medica' ? 'selected' : '' }}>Consulta médica</option>
                        <option value="emergencia" {{ old('servicio') == 'emergencia' ? 'selected' : '' }}>Servicio de emergencia</option>
                        <option value="rayos_x" {{ old('servicio') == 'rayos_x' ? 'selected' : '' }}>Examen de rayos X</option>
                    </select>
                    @error('servicio')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="descripcion_servicio">Descripción breve</label>
                    <input type="text" name="descripcion_servicio" maxlength="100" autocomplete="off" placeholder="Descripción breve" value="{{ old('descripcion_servicio') }}">
                    @error('descripcion_servicio')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="grid-2-cols" style="margin-top: 1.5rem;">
                <div>
                    <label for="cantidad">Cantidad</label>
                    <input type="number" name="cantidad" step="0.01" min="0.01" max="9999.99" placeholder="L. 0.00" value="{{ old('cantidad') }}">
                    @error('cantidad')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="fecha">Fecha</label>
                    <input type="date" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" readonly>
                    @error('fecha')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Mensaje para efectivo --}}
        <div id="efectivoMensaje" class="{{ old('metodo_pago') == 'efectivo' ? '' : 'hidden' }}">
            <div class="info-message">
                <strong>Pago pendiente:</strong> El pago se realizará en efectivo. El recibo se entregará al momento de cancelar.
            </div>
        </div>

        <div class="text-center pt-4" style="margin-top: 2rem;">

        
       <!-- Botón para tarjeta -->
       <div class="text-center pt-4" style="margin-top: 2rem;">
    <div id="btnContainer" class="d-flex justify-content-center gap-2"></div>
</div>

    <!-- Botón para efectivo 
    <a href="{{ route('inicio') }}"
       id="btnRegresar"
       class="btn btn-success btn-sm px-4 shadow-sm d-inline-flex align-items-center gap-2 hidden"
       style="font-size: 0.85rem;">
        <i class="bi bi-arrow-left"></i> Regresar
    </a>
</div> -->



    </form>

</div>

<script>
  function mostrarCamposPago() {
    const metodoSelect = document.getElementById('metodo_pago');
    const metodo = metodoSelect.value;

    // Mostrar u ocultar campos
    document.getElementById('tarjetaCampos').classList.toggle('hidden', metodo !== 'tarjeta');
    document.getElementById('efectivoMensaje').classList.toggle('hidden', metodo !== 'efectivo');

    // Botones dinámicos
    const btnContainer = document.getElementById('btnContainer');
    btnContainer.innerHTML = ''; // Limpiar

    // Siempre mostrar botón "Regresar"
    const btnRegresar = document.createElement('a');
    btnRegresar.href = '{{ route("inicio") }}';
    btnRegresar.id = 'btnRegresar';
    btnRegresar.className = 'btn btn-success btn-sm px-4 shadow-sm d-inline-flex align-items-center gap-2';
    btnRegresar.style.fontSize = '0.85rem';
    btnRegresar.innerHTML = '<i class="bi bi-arrow-left"></i> Regresar';
    btnContainer.appendChild(btnRegresar);

    // Solo si es tarjeta, agregar botón "Guardar"
    if (metodo === 'tarjeta') {
        const btnGuardar = document.createElement('button');
        btnGuardar.type = 'submit';
        btnGuardar.id = 'btnGuardar';
        btnGuardar.className = 'btn btn-primary btn-sm px-4 shadow-sm d-inline-flex align-items-center gap-2';
        btnGuardar.style.fontSize = '0.85rem';
        btnGuardar.innerHTML = '<i class="bi bi-plus-circle"></i> Guardar';
        btnContainer.appendChild(btnGuardar);
    }
}


    document.addEventListener('DOMContentLoaded', function () {
        mostrarCamposPago();

        const metodoPago = document.getElementById('metodo_pago').value;
        const btnGuardar = document.getElementById('btnGuardar');
        const btnRegresar = document.getElementById('btnRegresar');

        if (metodoPago !== 'tarjeta') {
            btnGuardar.classList.add('hidden');
        }

        if (metodoPago !== 'efectivo') {
            btnRegresar.classList.add('hidden');
        }

        // Campo "fecha" solo lectura, pero enviado
        const fechaInput = document.querySelector('input[name="fecha"]');
        if (fechaInput) {
            fechaInput.removeAttribute('disabled');
            fechaInput.setAttribute('readonly', true);
        }

        // Reglas de formato
        document.querySelector('input[name="cantidad"]').addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9.]/g, '').slice(0, 6);
        });

        document.querySelector('input[name="nombre_titular"]').addEventListener('input', function () {
            this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
        });

        document.querySelector('input[name="descripcion_servicio"]').addEventListener('input', function () {
            this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
        });

        document.querySelector('input[name="numero_tarjeta"]').addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 19);
        });

        document.querySelector('input[name="cvv"]').addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 4);
        });

        document.querySelector('input[name="fecha_expiracion"]').addEventListener('change', function () {
            const hoy = new Date();
            const seleccionada = new Date(this.value);
            const parent = this.parentElement;

            const mensajeAnterior = parent.querySelector('.error-text.fecha-expirada');
            if (mensajeAnterior) {
                mensajeAnterior.remove();
            }

            if (seleccionada < new Date(hoy.getFullYear(), hoy.getMonth(), 1)) {
                const errorDiv = document.createElement('div');
                errorDiv.classList.add('error-text', 'fecha-expirada');
                errorDiv.textContent = "La tarjeta ya expiró.";
                parent.appendChild(errorDiv);
            }
        });

        // Validación dinámica en tiempo real
        const inputs = document.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('input', function () {
                const parent = input.parentElement;
                const errorDiv = parent.querySelector('.error-text');
                const value = input.value.trim();
                const name = input.name;

                if (errorDiv) {
                    let esValido = false;

                    switch (name) {
                        case 'nombre_titular':
                        case 'descripcion_servicio':
                            esValido = /^[a-zA-Z\s]{1,50}$/.test(value);
                            break;
                        case 'numero_tarjeta':
                            esValido = /^\d{13,19}$/.test(value.replace(/\s/g, ''));
                            break;
                        case 'cvv':
                            esValido = /^\d{3,4}$/.test(value);
                            break;
                        case 'cantidad':
                            esValido = parseFloat(value) > 0;
                            break;
                        case 'servicio':
                            esValido = value !== '';
                            break;
                        default:
                            esValido = value !== '';
                    }

                    if (esValido) {
                        errorDiv.remove();
                    }
                }
            });
        });
    });
</script>

@endsection

