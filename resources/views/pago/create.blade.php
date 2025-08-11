@extends('layouts.app')

@section('content')
<style>
    .custom-card {
        max-width: 1000px;
        background-color: #e6f0ff;
        border: 1px solid #91cfff;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2);
        margin: 2rem auto;
        padding: 2rem;
    }
    .grid-3-cols > div {
    box-sizing: border-box;
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
    input[type="month"],
    select {
        width: 100%;
        padding: 0.6rem;
        margin-bottom: 1rem;
        border: 1px solid #b3d9ff;
        border-radius: 8px;
        background-color: #ffffff;
        font-family: inherit;
        font-size: 1rem;
    }

    .grid-3-cols {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .full-width {
        width: 100%;
        margin-bottom: 1.5rem;
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
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif


             <!-- Datos del paciente -->
            @if($paciente)
                <div class="grid-3-cols" style="margin-bottom: 1rem;">
                    <div>
                        <label>Nombre del paciente</label>
                        <input type="text" value="{{ $paciente->nombre }}" readonly class="form-control">
                    </div>
                    <div>
                        <label>Apellidos</label>
                        <input type="text" value="{{ $paciente->apellidos }}" readonly class="form-control">
                    </div>
                    <div>
                        <label>Identidad</label>
                        <input type="text" value="{{ $paciente->identidad }}" readonly class="form-control">
                    </div>
                </div>
            @endif


            <!-- Fila 1: Nombre del Titular, Número de Tarjeta y CVV -->
            <div class="grid-3-cols">
                <div>
                    <label for="nombre_titular">Nombre del Titular</label>
                    <input type="text" name="nombre_titular" maxlength="50" autocomplete="off" placeholder="Nombre en la tarjeta" value="{{ old('nombre_titular') }}">
                    @error('nombre_titular')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="numero_tarjeta">Número de Tarjeta</label>
                    <input type="text" name="numero_tarjeta" maxlength="19" autocomplete="off" placeholder="0000 0000 0000 0000" value="{{ old('numero_tarjeta') }}">
                    @error('numero_tarjeta')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="cvv">CVV</label>
                    <input type="text" name="cvv" maxlength="4" autocomplete="off" placeholder="123" value="{{ old('cvv') }}">
                    @error('cvv')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Fila 2: Servicio, Fecha de Expiración y Cantidad -->
            <div class="grid-3-cols">
            <div>
                <label for="servicio_efectivo">Servicio</label>
                <input type="text" name="servicio_efectivo" id="servicio_efectivo"
                    value="{{ old('servicio', $servicio) }}" readonly>
            </div>
                <div>
                    <label for="fecha_expiracion">Fecha de Expiración</label>
                    <input type="month" name="fecha_expiracion" value="{{ old('fecha_expiracion') }}">
                       @error('fecha_expiracion')
                        <div class="error-text">{{ $message }}</div>
                    @enderror 
                </div>


                <div class="form-group">
                    <label for="cantidad">Cantidad</label>
                    <input type="text" name="cantidad" placeholder="L. 0.00" 
                value="{{ old('cantidad', number_format($cantidad, 2, '.', '')) }}" readonly>
                   <!-- @error('cantidad')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror -->
                </div>
            </div>

            <!-- Fila 3: Fecha y Descripción breve -->
            <div class="grid-3-cols">
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

        {{-- Campos para efectivo --}}
        <div id="efectivoCampos" class="{{ old('metodo_pago') == 'efectivo' ? '' : 'hidden' }}">
            @if($paciente)
                <div class="grid-3-cols" style="margin-bottom: 1rem;">
                    <div>
                        <label>Nombre del paciente</label>
                        <input type="text" value="{{ $paciente->nombre }}" readonly class="form-control">
                    </div>
                    <div>
                        <label>Apellidos</label>
                        <input type="text" value="{{ $paciente->apellidos }}" readonly class="form-control">
                    </div>
                    <div>
                        <label>Identidad</label>
                        <input type="text" value="{{ $paciente->identidad }}" readonly class="form-control">
                    </div>
                </div>
            @endif

            <div class="grid-3-cols">
            <div>
                <label for="servicio_efectivo">Servicio</label>
                <input type="text" name="servicio_efectivo" id="servicio_efectivo"
                    value="{{ old('servicio', $servicio) }}" readonly>
            </div>
                <div class="form-group">
                    <label for="cantidad">Cantidad</label>
                    <input type="text" name="cantidad" placeholder="L. 0.00" 
                    value="{{ old('cantidad', number_format($cantidad, 2, '.', '')) }}" readonly>
                     @error('cantidad')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                        <label for="fecha_efectivo">Fecha</label>
                        <input type="date" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" readonly>
                        @error('fecha')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
        </div>

        <input type="hidden" name="consulta_id" value="{{ $consulta->id ?? '' }}">
        @if($servicio && $cantidad)
    <input type="hidden" name="servicio_tarjeta" value="{{ $servicio }}">
    <input type="hidden" name="servicio_efectivo" value="{{ $servicio }}">
    <input type="hidden" name="descripcion_servicio" placeholder="Descripción opcional o dinámica si la tienes">
@endif


        <div class="text-center pt-4" style="margin-top: 2rem;">
            <div id="btnContainer" class="d-flex justify-content-center gap-2"></div>
        </div>
        
    </form>
</div>

<script>
function mostrarCamposPago() {
    const metodoSelect = document.getElementById('metodo_pago');
    const metodo = metodoSelect.value;

    document.getElementById('tarjetaCampos').classList.toggle('hidden', metodo !== 'tarjeta');
    document.getElementById('efectivoMensaje').classList.toggle('hidden', metodo !== 'efectivo');
    document.getElementById('efectivoCampos').classList.toggle('hidden', metodo !== 'efectivo');

    const servicio = document.getElementById('servicio');
    const cantidad = document.getElementById('cantidad');

    if (metodo === 'tarjeta') {
    const servicio = document.getElementById('servicio_tarjeta');
    const cantidad = document.querySelector('input[name="cantidad_tarjeta"]');
    if (servicio) servicio.disabled = false;
    if (cantidad) cantidad.disabled = false;
} else if (metodo === 'efectivo') {
    const servicio = document.getElementById('servicio_efectivo');
    const cantidad = document.querySelector('#efectivoCampos input[name="cantidad"]');
    if (servicio) servicio.disabled = false;
    if (cantidad) cantidad.disabled = false;
} else {
    // Opcional: deshabilitar todo si no hay método seleccionado
    const servicioTarjeta = document.getElementById('servicio_tarjeta');
    const cantidadTarjeta = document.querySelector('input[name="cantidad_tarjeta"]');
    const servicioEfectivo = document.getElementById('servicio_efectivo');
    const cantidadEfectivo = document.querySelector('#efectivoCampos input[name="cantidad"]');
    if (servicioTarjeta) servicioTarjeta.disabled = true;
    if (cantidadTarjeta) cantidadTarjeta.disabled = true;
    if (servicioEfectivo) servicioEfectivo.disabled = true;
    if (cantidadEfectivo) cantidadEfectivo.disabled = true;
}


    const btnContainer = document.getElementById('btnContainer');
    btnContainer.innerHTML = '';

    if (metodo === 'tarjeta') {
        const btnRegresar = document.createElement('a');
        btnRegresar.href = '{{ route("inicio") }}';
        btnRegresar.id = 'btnRegresar';
        btnRegresar.className = 'btn btn-success btn-sm px-4 shadow-sm d-inline-flex align-items-center gap-2';
        btnRegresar.style.fontSize = '0.85rem';
        btnRegresar.innerHTML = '<i class="bi bi-arrow-left"></i> Regresar';
        btnContainer.appendChild(btnRegresar);

        const btnGuardar = document.createElement('button');
        btnGuardar.type = 'submit';
        btnGuardar.id = 'btnGuardar';
        btnGuardar.className = 'btn btn-primary btn-sm px-4 shadow-sm d-inline-flex align-items-center gap-2';
        btnGuardar.style.fontSize = '0.85rem';
        btnGuardar.innerHTML = '<i class="bi bi-plus-circle"></i> Guardar';
        btnContainer.appendChild(btnGuardar);
        btnGuardar.addEventListener('click', function() {
        this.disabled = true;
        this.form.submit();
    });

    }

    if (metodo === 'efectivo') {
        const btnGuardarEfectivo = document.createElement('button');
        btnGuardarEfectivo.type = 'submit';
        btnGuardarEfectivo.id = 'btnGuardarEfectivo';
        btnGuardarEfectivo.className = 'btn btn-primary btn-sm px-4 shadow-sm d-inline-flex align-items-center gap-2';
        btnGuardarEfectivo.style.fontSize = '0.85rem';
        btnGuardarEfectivo.innerHTML = '<i class="bi bi-check-circle"></i> Guardar';
        btnContainer.appendChild(btnGuardarEfectivo);

        btnGuardarEfectivo.addEventListener('click', function() {
            this.disabled = true;
            this.form.submit();
        });
    }
}

document.addEventListener('DOMContentLoaded', function () {
    mostrarCamposPago();

    const fechaInput = document.querySelector('input[name="fecha"]');
    if (fechaInput) {
        fechaInput.removeAttribute('disabled');
        fechaInput.setAttribute('readonly', true);
    }

    // Auto-llenar descripción si servicio es consulta médica
    const servicioSelect = document.getElementById('servicio');
    const descripcionInput = document.querySelector('input[name="descripcion_servicio"]');
    if (servicioSelect && descripcionInput) {
        servicioSelect.addEventListener('change', function () {
            if (this.value === 'consulta_medica') {
                descripcionInput.value = "Pago por consulta médica";
            } else if (descripcionInput.value === "Pago por consulta médica") {
                descripcionInput.value = "";
            }
        });
    }

    // Validaciones de inputs (tu lógica previa)
    const cantidadInput = document.querySelector('input[name="cantidad"]');
    if (cantidadInput) {
        cantidadInput.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9.]/g, '').slice(0, 6);
        });
    }

    const nombreTitularInput = document.querySelector('input[name="nombre_titular"]');
    if (nombreTitularInput) {
        nombreTitularInput.addEventListener('input', function () {
            this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]/g, '');
        });
    }

    const descripcionInput2 = document.querySelector('input[name="descripcion_servicio"]');
    if (descripcionInput2) {
        descripcionInput2.addEventListener('input', function () {
            this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]/g, '');
        });
    }

    const numeroTarjetaInput = document.querySelector('input[name="numero_tarjeta"]');
    if (numeroTarjetaInput) {
        numeroTarjetaInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 19);
        });
    }

    const cvvInput = document.querySelector('input[name="cvv"]');
    if (cvvInput) {
        cvvInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 4);
        });
    }

    const fechaExpiracionInput = document.querySelector('input[name="fecha_expiracion"]');
    if (fechaExpiracionInput) {
        fechaExpiracionInput.addEventListener('change', function () {
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
    }

    // --- VALIDACIONES MÉTODO EFECTIVO ---

    const servicioEfectivoSelect = document.querySelector('#efectivoCampos select[name="servicio"]');
    const cantidadEfectivoInput = document.querySelector('#efectivoCampos input[name="cantidad"]');
    const descripcionEfectivoInput = document.querySelector('#efectivoCampos input[name="descripcion_servicio"]');

    function mostrarError(input, mensaje) {
        eliminarError(input);
        const errorDiv = document.createElement('div');
        errorDiv.classList.add('error-text');
        errorDiv.textContent = mensaje;
        input.parentNode.appendChild(errorDiv);
    }

    function eliminarError(input) {
        const parent = input.parentNode;
        const error = parent.querySelector('.error-text');
        if (error) error.remove();
    }

    if (cantidadEfectivoInput) {
        cantidadEfectivoInput.addEventListener('input', function () {
            let val = this.value;

            val = val.replace(/[^0-9.]/g, '');

            const parts = val.split('.');
            if (parts.length > 2) {
                val = parts[0] + '.' + parts[1];
            }

            if (parts[0].length > 5) {
                parts[0] = parts[0].slice(0, 5);
            }

            if (parts[1]) {
                parts[1] = parts[1].slice(0, 2);
                val = parts[0] + '.' + parts[1];
            } else {
                val = parts[0];
            }

            this.value = val;

            eliminarError(this);
        });
    }

    if (descripcionEfectivoInput) {
        descripcionEfectivoInput.addEventListener('input', function () {
            this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]/g, '').slice(0, 100);
            eliminarError(this);
        });
    }

    if (servicioEfectivoSelect) {
        servicioEfectivoSelect.addEventListener('change', function () {
            eliminarError(this);
        });
    }

    const form = document.querySelector('form');
    form.addEventListener('submit', function (event) {
        const metodo = document.getElementById('metodo_pago').value;

        if (metodo === 'efectivo') {
            let hayError = false;

            if (servicioEfectivoSelect.value === '') {
                mostrarError(servicioEfectivoSelect, 'Por favor, selecciona un servicio.');
                hayError = true;
            }

            const cantidadVal = cantidadEfectivoInput.value.trim();
            if (cantidadVal === '') {
                mostrarError(cantidadEfectivoInput, 'La cantidad es obligatoria.');
                hayError = true;
            } else {
                if (!/^\d{1,5}(\.\d{1,2})?$/.test(cantidadVal)) {
                    mostrarError(cantidadEfectivoInput, 'Cantidad inválida. Máximo 5 enteros y 2 decimales.');
                    hayError = true;
                } else if (parseFloat(cantidadVal) <= 0) {
                    mostrarError(cantidadEfectivoInput, 'La cantidad debe ser mayor que cero.');
                    hayError = true;
                }
            }

            const descripcionVal = descripcionEfectivoInput.value.trim();
            if (descripcionVal !== '') {
                if (!/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]{1,100}$/.test(descripcionVal)) {
                    mostrarError(descripcionEfectivoInput, 'La descripción solo debe contener letras y espacios, máximo 100 caracteres.');
                    hayError = true;
                }
            }

            if (hayError) {
                event.preventDefault();
            }
        }
    });
});

const inputCantidad = document.querySelector('input[name="cantidad"]');

inputCantidad.addEventListener('input', function (e) {
    let val = this.value.replace(/\D/g, '');

    if (val === '') {
        this.value = '';
        return;
    }

    val = parseInt(val, 10).toString();

    while (val.length < 3) {
        val = '0' + val;
    }

    const len = val.length;
    const formatted = val.substring(0, len - 2) + '.' + val.substring(len - 2);

    this.value = formatted;
});
</script>

@endsection