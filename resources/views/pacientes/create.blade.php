@extends('layouts.app')

@section('content')



<style>
.custom-card {
    max-width: 1000px;
    background-color: rgba(255, 255, 255, 0.95);
    border: 1px solid #91cfff;
    border-radius: 0.5rem;
    position: relative;
    overflow: hidden;
    margin: 2rem auto;
    padding: 2rem;
    box-shadow: 0 0 15px rgba(0,123,255,0.25);
    z-index: 1;
}

.custom-card::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 800px;
    height: 800px;
    background-image: url('/images/logo2.jpg');
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    opacity: 0.15;
    transform: translate(-50%, -50%);
    pointer-events: none;
    z-index: 0;
}
</style>

<div class="custom-card">
    <!-- Título del formulario con borde azul abajo -->
    <div class="mb-4 text-center" style="border-bottom: 3px solid #007BFF;">
        <h2 class="fw-bold text-black mb-0">Registro de paciente</h2>
    </div>

    <form id="formPaciente" action="{{ route('pacientes.store') }}" method="POST" class="needs-validation" novalidate>

        @csrf

        <h5 class="mb-3 text-dark fw-bold">Datos básicos y contacto</h5>

        <!-- Fila 1 -->
        <div class="row mb-3">
        <div class="col-md-3">
    <label for="nombre" class="form-label">Nombre(s): <span class="text-danger">*</span></label>
    <input
        type="text"
        name="nombre"
        id="nombre"
        maxlength="50"
        required
        pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$"
        title="Solo letras (mayúsculas, minúsculas, acentos) y espacios, máximo 50 caracteres"
        class="form-control @error('nombre') is-invalid @enderror"
        value="{{ old('nombre', $paciente->nombre ?? '') }}">
    @error('nombre')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

<div class="col-md-3">
    <label for="apellidos" class="form-label">Apellidos: <span class="text-danger">*</span></label>
    <input
        type="text"
        name="apellidos"
        id="apellidos"
        maxlength="50"
        required
        pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$"
        title="Solo letras (mayúsculas, minúsculas, acentos) y espacios, máximo 50 caracteres"
        class="form-control @error('apellidos') is-invalid @enderror"
        value="{{ old('apellidos', $paciente->apellidos ?? '') }}">
    @error('apellidos')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>


<div class="col-md-3">
    <label for="identidad" class="form-label">Identidad: <span class="text-danger">*</span></label>
    <input type="text" name="identidad" id="identidad" maxlength="13"
           pattern="^(0[1-9]|1[0-8])(0[1-9]|1[0-9]|2[0-8])[0-9]{9}$" required
           class="form-control @error('identidad') is-invalid @enderror"
           value="{{ old('identidad') }}"
           placeholder="Ej: 0703200201564 (Depto 01-18, Mun 01-28, Año + 5 dígitos)"
           title="Debe ingresar 13 números válidos. Formato: Departamento(01-18) Municipio(01-28) + 9 dígitos.">
    <div id="errorIdentidad" class="invalid-feedback d-block" style="display:none;"></div>
    @error('identidad')
    <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>



            <div class="col-md-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento: <span class="text-danger">*</span></label>
                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required
                       class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                       value="{{ old('fecha_nacimiento') }}"
                       min="{{ \Carbon\Carbon::now()->subYears(60)->format('Y-m-d') }}"
                       max="{{ \Carbon\Carbon::now()->subYears(21)->format('Y-m-d') }}">
                @error('fecha_nacimiento')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <label for="telefono" class="form-label">Teléfono: <span class="text-danger">*</span></label>
                <input type="tel" name="telefono" id="telefono" maxlength="8"
                       pattern="^[2389][0-9]{7}$" required
                       class="form-control @error('telefono') is-invalid @enderror"
                       value="{{ old('telefono') }}"
                       title="Debe comenzar con 2, 3, 8 o 9 y tener 8 dígitos.">
                @error('telefono')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label for="correo" class="form-label">Correo electrónico: <span class="text-danger">*</span></label>
                <input type="email" name="correo" id="correo" maxlength="50" required
                       class="form-control @error('correo') is-invalid @enderror"
                       value="{{ old('correo') }}"
                       placeholder="ejemplo@dominio.com">
                @error('correo')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label for="tipo_sangre" class="form-label">Tipo de Sangre (opcional):</label>
                <select name="tipo_sangre" id="tipo_sangre" class="form-select @error('tipo_sangre') is-invalid @enderror">
                    <option value="">Seleccione...</option>
                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $tipo)
                        <option value="{{ $tipo }}" {{ old('tipo_sangre') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                    @endforeach
                </select>
                @error('tipo_sangre')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label for="genero" class="form-label">Género: <span class="text-danger">*</span></label>
                <select name="genero" id="genero" required class="form-select @error('genero') is-invalid @enderror">
                    <option value="">Seleccione...</option>
                    <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                    <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                    <option value="Otro" {{ old('genero') == 'Otro' ? 'selected' : '' }}>Otro</option>
                </select>
                @error('genero')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="direccion" class="form-label">Dirección: <span class="text-danger">*</span></label>
                <textarea name="direccion" id="direccion" rows="2" maxlength="300" required
                          class="form-control @error('direccion') is-invalid @enderror"
                          style="resize: vertical;">{{ old('direccion') }}</textarea>
                @error('direccion')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Sección clínica -->
        <h5 class="mt-4 mb-3 text-dark fw-bold">Datos clínicos</h5>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="padecimientos" class="form-label">Padecimientos:</label>
                <textarea name="padecimientos" id="padecimientos" rows="2" maxlength="200" required
                          class="form-control @error('padecimientos') is-invalid @enderror">{{ old('padecimientos') }}</textarea>
                @error('padecimientos')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="medicamentos" class="form-label">Medicamentos que consume:</label>
                <textarea name="medicamentos" id="medicamentos" rows="2" maxlength="200" required
                          class="form-control @error('medicamentos') is-invalid @enderror">{{ old('medicamentos') }}</textarea>
                @error('medicamentos')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="historial_clinico" class="form-label">Historial clínico:</label>
                <textarea name="historial_clinico" id="historial_clinico" rows="2" maxlength="200" required
                          class="form-control @error('historial_clinico') is-invalid @enderror">{{ old('historial_clinico') }}</textarea>
                @error('historial_clinico')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="alergias" class="form-label">Alergias conocidas:</label>
                <textarea name="alergias" id="alergias" rows="2" maxlength="200" required
                          class="form-control @error('alergias') is-invalid @enderror">{{ old('alergias') }}</textarea>
                @error('alergias')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <label for="historial_quirurgico" class="form-label">Historial quirúrgico (opcional):</label>
            <textarea name="historial_quirurgico" id="historial_quirurgico" rows="2" maxlength="200"
                      class="form-control @error('historial_quirurgico') is-invalid @enderror">{{ old('historial_quirurgico') }}</textarea>
            @error('historial_quirurgico')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- Botones centrados -->
        <div class="d-flex justify-content-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Registrar
            </button>
            <button type="button" id="btnLimpiar" class="btn btn-warning">
    <i class="bi bi-trash"></i> Limpiar
</button>

            <a href="{{ route('pacientes.index') }}" class="btn btn-success">
                <i class="bi bi-arrow-left"></i> Regresar
            </a>
        </div>
    </form>
</div>

<!-- Script de limpieza -->
<script>
document.getElementById('btnLimpiar').addEventListener('click', () => {
    const form = document.getElementById('formPaciente');

    // Vaciar inputs, selects y textareas
    form.querySelectorAll('input, select, textarea').forEach(el => {
        if (el.type === 'checkbox' || el.type === 'radio') {
            el.checked = false;
        } else if (el.tagName.toLowerCase() === 'select') {
            el.selectedIndex = 0; // primer option (normalmente "Seleccione...")
        } else {
            el.value = '';
        }
        el.classList.remove('is-invalid', 'is-valid');
    });

    // Limpiar mensajes de validación
    form.querySelectorAll('.invalid-feedback').forEach(el => {
        el.textContent = '';
        el.style.display = 'none';
    });
});

</script>
<script>
function validarAnioIdentidad() {
    const valor = inputIdentidad.value.trim();

    if (!/^\d{13}$/.test(valor)) {
        inputIdentidad.setCustomValidity('');
        return true;
    }

    const anio = parseInt(valor.substring(4, 8), 10);
    const anioMin = 1940;
    const anioMax = new Date().getFullYear();

    if (anio < anioMin || anio > anioMax) {
        inputIdentidad.setCustomValidity(`El año en la identidad debe estar entre ${anioMin} y ${anioMax}.`);
        inputIdentidad.reportValidity();
        return false;
    } else {
        inputIdentidad.setCustomValidity('');
    }

    return true;
}

</script>


@endsection
