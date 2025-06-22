@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #e8f4fc;
    }
    .custom-card {
        max-width: 97%;
        background-color: #f0faff;
        border-color: #91cfff;
    }
    label {
        font-size: 0.85rem;
    }
    input, select, textarea {
        font-size: 0.85rem !important;
    }
</style>
<div class="header d-flex justify-content-between align-items-center px-3 py-2" style="background-color: #007BFF;">
    <div class="fw-bold text-white" style="font-size: 1.5rem;">Clinitek</div>
    <div class="d-flex gap-3 flex-wrap">
        <a href="{{ route('puestos.create') }}" class="text-decoration-none text-white fw-semibold">Crear puesto</a>
        <a href="{{ route('empleado.create') }}" class="text-decoration-none text-white fw-semibold">Registrar empleado</a>
        
    </div>
</div>
<div class="d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 56px - 100px); margin-top: 2rem;">
    <div class="card custom-card shadow-sm border rounded-4 w-100 mt-0">
        <div class="card-header bg-primary text-white py-2">
            <h2 class="mb-0">Registrar nuevo médico</h2>
        </div>



        <div class="card-body">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @php
                    $maxNacimiento = now()->subYears(18)->format('Y-m-d');
                    $minIngreso = now()->subMonth()->format('Y-m-d');
                    $maxIngreso = now()->addMonth()->format('Y-m-d');
                @endphp

                <form method="POST" action="{{ route('medicos.store') }}" enctype="multipart/form-data" id="formMedico" novalidate>
                    @csrf

                    <div class="row mb-3">
    <div class="col-md-3">
        {{-- Nombre --}}
        <label for="nombre" class="form-label">Nombre: <span class="text-danger">*</span></label>
        <input type="text" name="nombre" maxlength="50" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
            class="form-control @error('nombre') is-invalid @enderror {{ old('nombre') && !$errors->has('nombre') ? 'is-valid' : '' }}"
            value="{{ old('nombre') }}" required>
        @error('nombre')
            <div class="invalid-feedback">{{ $message ?: 'Solo se permiten letras. Ingrese este dato.' }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        {{-- Apellidos --}}
        <label for="apellidos" class="form-label">Apellidos: <span class="text-danger">*</span></label>
        <input type="text" name="apellidos" maxlength="50" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
            class="form-control @error('apellidos') is-invalid @enderror {{ old('apellidos') && !$errors->has('apellidos') ? 'is-valid' : '' }}"
            value="{{ old('apellidos') }}" required>
        @error('apellidos')
            <div class="invalid-feedback">{{ $message ?: 'Solo se permiten letras.' }}</div>
        @enderror
    </div>

    <div class="col-md-3">
    {{-- Identidad --}}
    <label for="numero_identidad" class="form-label">Identidad: <span class="text-danger">*</span></label>
    <input type="text" name="numero_identidad" maxlength="13" minlength="13"
        pattern="^(0[1-9]|1[0-8])[0-9]{11}$"
        class="form-control @error('numero_identidad') is-invalid @enderror {{ old('numero_identidad') && !$errors->has('numero_identidad') ? 'is-valid' : '' }}"
        value="{{ old('numero_identidad') }}" required>
    @error('numero_identidad')
        <div class="invalid-feedback">
            @if(str_contains($message, 'required'))
                Ingrese este dato.
            @elseif(str_contains($message, 'digits') || str_contains($message, 'size'))
                Debe tener exactamente 13 números.
            @elseif(str_contains($message, 'regex'))
                Solo se admiten números y debe iniciar con un código de departamento válido (01-18).
            @elseif(str_contains($message, 'unique'))
                Este número de identidad ya está registrado.
            @else
                {{ $message }}
            @endif
        </div>
    @enderror
</div>

    <div class="col-md-3">
        {{-- Fecha de nacimiento --}}
        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento: <span class="text-danger">*</span></label>
        <input type="date" name="fecha_nacimiento"
            class="form-control @error('fecha_nacimiento') is-invalid @enderror {{ old('fecha_nacimiento') && !$errors->has('fecha_nacimiento') ? 'is-valid' : '' }}"
            value="{{ old('fecha_nacimiento') }}"
            required
            max="{{ \Carbon\Carbon::now()->subYears(18)->format('Y-m-d') }}">
        @error('fecha_nacimiento')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-3">
        {{-- Teléfono --}}
        <label for="telefono" class="form-label">Teléfono: <span class="text-danger">*</span></label>
        <input type="text" name="telefono" maxlength="8" minlength="8"
            class="form-control @error('telefono') is-invalid @enderror {{ old('telefono') && !$errors->has('telefono') ? 'is-valid' : '' }}"
            value="{{ old('telefono') }}" required>
        @error('telefono')
            <div class="invalid-feedback">
                @if(str_contains($message, 'required'))
                    Ingrese este dato.
                @elseif(str_contains($message, 'digits'))
                    Debe contener exactamente 8 números.
                @elseif(str_contains($message, 'regex'))
                    Debe iniciar con 2, 3, 8 o 9 y solo contener números.
                @elseif(str_contains($message, 'numeric'))
                    Solo se admiten números.
                @elseif(str_contains($message, 'unique'))
                    Este número de teléfono ya existe.
                @else
                    {{ $message }}
                @endif
            </div>
        @enderror
    </div>

    <div class="col-md-3">
        {{-- Correo --}}
        <label for="correo" class="form-label">Correo: <span class="text-danger">*</span></label>
        <input type="email" name="correo" maxlength="50"
            placeholder="ejemplo@dominio.com"
            class="form-control @error('correo') is-invalid @enderror {{ old('correo') && !$errors->has('correo') ? 'is-valid' : '' }}"
            value="{{ old('correo') }}" required>
        @error('correo')
            <div class="invalid-feedback">
                @if(str_contains($message, 'required'))
                    Ingrese este dato.
                @elseif(str_contains($message, 'regex'))
                    El correo debe contener un @ y un dominio válido como .com, .es, .edu, etc.
                @elseif(str_contains($message, 'unique'))
                    Este correo electrónico ya existe.
                @else
                    {{ $message }}
                @endif
            </div>
        @enderror
    </div>

    <div class="col-md-3">
        {{-- Género --}}
        <label for="genero" class="form-label">Género: <span class="text-danger">*</span></label>
        <select name="genero" class="form-select @error('genero') is-invalid @enderror {{ old('genero') && !$errors->has('genero') ? 'is-valid' : '' }}" required>
            <option value="">Seleccionar</option>
            <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
            <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
            <option value="Otro" {{ old('genero') == 'Otro' ? 'selected' : '' }}>Otro</option>
        </select>
        @error('genero')
            <div class="invalid-feedback">Por favor, elija una opción.</div>
        @else
            @if (old('genero') === '')
                <div class="invalid-feedback d-block">Elija una opción para el campo género.</div>
            @endif
        @enderror
    </div>

    <div class="col-md-3">
        {{-- Especialidad --}}
        <label for="especialidad" class="form-label">Especialidad: <span class="text-danger">*</span></label>
        <select name="especialidad" id="especialidad" class="form-select @error('especialidad') is-invalid @enderror {{ old('especialidad') && !$errors->has('especialidad') ? 'is-valid' : '' }}" required>
            <option value="">Seleccionar</option>
            <option value="Cardiología" {{ old('especialidad') == 'Cardiología' ? 'selected' : '' }}>Cardiología</option>
            <option value="Neurología" {{ old('especialidad') == 'Neurología' ? 'selected' : '' }}>Neurología</option>
            <option value="Pediatría" {{ old('especialidad') == 'Pediatría' ? 'selected' : '' }}>Pediatría</option>
            <option value="Dermatología" {{ old('especialidad') == 'Dermatología' ? 'selected' : '' }}>Dermatología</option>
            <option value="Psiquiatría" {{ old('especialidad') == 'Psiquiatría' ? 'selected' : '' }}>Psiquiatría</option>
            <option value="Radiología" {{ old('especialidad') == 'Radiología' ? 'selected' : '' }}>Radiología</option>
        </select>
        @error('especialidad')
            <div class="invalid-feedback">Seleccione una especialidad.</div>
        @enderror
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-3">
        {{-- Salario --}}
        <label class="form-label">Salario</label>
        <input type="text" id="salario_mostrado" class="form-control" readonly>
        <input type="hidden" name="salario" id="salario_real" value="{{ old('salario', $medico->salario ?? '') }}">
    </div>

    <div class="col-md-3">
        {{-- Fecha de ingreso --}}
        <label for="fecha_ingreso" class="form-label">Fecha de Ingreso: <span class="text-danger">*</span></label>
        <input type="date" name="fecha_ingreso"
            class="form-control @error('fecha_ingreso') is-invalid @enderror {{ old('fecha_ingreso') && !$errors->has('fecha_ingreso') ? 'is-valid' : '' }}"
            value="{{ old('fecha_ingreso') }}"
            required
            min="{{ $minIngreso }}"
            max="{{ $maxIngreso }}">
        @error('fecha_ingreso')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        {{-- Foto --}}
        <label for="foto" class="form-label">Foto:</label>
        <input type="file" name="foto" id="foto" class="form-control form-control-sm @error('foto') is-invalid @enderror" accept="image/*">
        @error('foto')
            <div class="invalid-feedback">Suba una imagen válida. Opcional.</div>
        @enderror
        <div class="form-text">Opcional. Formato: JPG, PNG.</div>
    </div>

    <div class="col-md-3">
        {{-- Dirección --}}
        <label for="direccion" class="form-label">Dirección: <span class="text-danger">*</span></label>
        <textarea name="direccion" maxlength="300"
            class="form-control @error('direccion') is-invalid @enderror {{ old('direccion') && !$errors->has('direccion') ? 'is-valid' : '' }}"
            rows="3"
            required>{{ old('direccion') }}</textarea>
        @error('direccion')
            <div class="invalid-feedback">
                @if(str_contains($message, 'required'))
                    Ingrese este dato.
                @elseif(str_contains($message, 'max'))
                    No puede exceder 300 caracteres.
                @else
                    {{ $message }}
                @endif
            </div>
        @enderror
        <div class="form-text">Máximo 300 caracteres.</div>
    </div>
</div>

<div class="mb-2" style="max-width: 400px;">
    <label class="form-label">Observaciones:</label>
    <textarea name="observaciones" maxlength="200" class="form-control @error('observaciones') is-invalid @enderror" rows="3">{{ old('observaciones') }}</textarea>
    @error('observaciones')
        <div class="invalid-feedback">No puede exceder 200 caracteres.</div>
    @enderror
    <div class="form-text">Máximo 200 caracteres.</div>
</div>


<div class="d-flex justify-content-center mt-4">
    <!-- Botón Registrar: azul con ícono plus-circle -->
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Registrar
    </button>

    <!-- Botón Limpiar -->
    <button type="button" id="btnLimpiar" class="btn btn-warning ms-2">
        <i class="bi bi-trash"></i> Limpiar
    </button>

    <!-- Botón Regresar: verde con ícono arrow-left -->
    <a href="{{ route('medicos.index') }}" class="btn btn-success ms-2">
        <i class="bi bi-arrow-left"></i> Regresar
    </a>
</div>


                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formMedico');
    const btnLimpiar = document.getElementById('btnLimpiar');
    const especialidadSelect = document.getElementById('especialidad');
    const salarioMostrado = document.getElementById('salario_mostrado');
    const salarioReal = document.getElementById('salario_real');

    const salariosPorEspecialidad = {
        "Cardiología": 15000,
        "Neurología": 24800,
        "Pediatría": 27500,
        "Dermatología": 14200,
        "Psiquiatría": 14700,
        "Radiología": 16300
    };

    function formatearMoneda(valor) {
        return 'L ' + Number(valor).toLocaleString('es-HN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function actualizarSalario() {
        const especialidad = especialidadSelect.value;
        if (salariosPorEspecialidad.hasOwnProperty(especialidad)) {
            const salario = salariosPorEspecialidad[especialidad];
            salarioReal.value = salario;
            salarioMostrado.value = formatearMoneda(salario);
        } else {
            salarioReal.value = '';
            salarioMostrado.value = '';
        }
    }

    especialidadSelect.addEventListener('change', actualizarSalario);

    btnLimpiar.addEventListener('click', function () {
        form.reset(); // Resetea todos los campos del formulario
        salarioMostrado.value = ''; // Limpiar campo mostrado (readonly)
        salarioReal.value = '';      // Limpiar campo oculto real

        // Quitar clases de validación
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.classList.remove('is-invalid', 'is-valid');
        });

        // Ocultar mensajes de error
        const feedbacks = form.querySelectorAll('.invalid-feedback');
        feedbacks.forEach(div => div.style.display = 'none');
    });

    // Al cargar la página, actualizar salario si ya hay especialidad
    if (especialidadSelect.value) {
        actualizarSalario();
    } else if (salarioReal.value) {
        salarioMostrado.value = formatearMoneda(salarioReal.value);
    }
});
</script>


@endsection
