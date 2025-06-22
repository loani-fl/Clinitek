@extends('layouts.app')

@section('content')
<!-- Barra de navegación superior -->
<div class="header d-flex justify-content-between align-items-center px-3 py-2" style="background-color: #007BFF; position: sticky; top: 0; z-index: 1030;">
    <div class="fw-bold text-white" style="font-size: 1.5rem;">Clinitek</div>
    <div class="d-flex gap-3 flex-wrap">
        <a href="{{ route('puestos.create') }}" class="text-decoration-none text-white fw-semibold">Crear puesto</a>
        <a href="{{ route('empleado.create') }}" class="text-decoration-none text-white fw-semibold">Registrar empleado</a>
        <a href="{{ route('medicos.create') }}" class="text-decoration-none text-white fw-semibold">Registrar médico</a>
    </div>
</div>

<!-- Contenedor principal con fondo azul claro -->
<div class="container-fluid py-4" style="background-color: #e8f4fc; min-height: 100vh;">
    <div class="mx-auto shadow rounded border" style="max-width: 1100px; background-color: #fff;">

        <!-- Título dentro del formulario, sin margen, con bordes redondeados arriba -->
        <div style="background-color: #007BFF; padding: 20px 40px; border-top-left-radius: 10px; border-top-right-radius: 10px; width: 100%;">
            <h5 class="mb-0 text-white">Registro de Paciente</h5>
        </div>

        <form id="formPaciente" action="{{ route('pacientes.store') }}" method="POST" class="p-4" novalidate>
            @csrf

            <!-- Título de sección -->
            <h5 class="mt-4 mb-3 text-dark fw-bold">Datos básicos y contacto</h5>

            <!-- Fila 1 -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="nombre" class="form-label">Nombre(s): <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" id="nombre" maxlength="50" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" required
                        class="form-control @error('nombre') is-invalid @enderror"
                        value="{{ old('nombre') }}">
                    @error('nombre')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="apellidos" class="form-label">Apellidos: <span class="text-danger">*</span></label>
                    <input type="text" name="apellidos" id="apellidos" maxlength="50" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" required
                        class="form-control @error('apellidos') is-invalid @enderror"
                        value="{{ old('apellidos') }}">
                    @error('apellidos')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="identidad" class="form-label">Identidad: <span class="text-danger">*</span></label>
                    <input type="text" name="identidad" id="identidad" maxlength="13" pattern="^(0[1-9]|1[0-8])[0-9]{11}$" required
                        class="form-control @error('identidad') is-invalid @enderror"
                        value="{{ old('identidad') }}">
                    @error('identidad')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento: <span class="text-danger">*</span></label>
                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required
                        class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                        value="{{ old('fecha_nacimiento') }}" max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                    @error('fecha_nacimiento')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Fila 2 -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="telefono" class="form-label">Teléfono: <span class="text-danger">*</span></label>
                    <input type="tel" name="telefono" id="telefono" maxlength="8" pattern="^[2389][0-9]{7}$" required
                        class="form-control @error('telefono') is-invalid @enderror"
                        value="{{ old('telefono') }}">
                    @error('telefono')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="correo" class="form-label">Correo electrónico: <span class="text-danger">*</span></label>
                    <input type="email" name="correo" id="correo" maxlength="55" required
                        class="form-control @error('correo') is-invalid @enderror"
                        value="{{ old('correo') }}" placeholder="ejemplo@dominio.com">
                    @error('correo')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="direccion" class="form-label">Dirección: <span class="text-danger">*</span></label>
                    <textarea name="direccion" id="direccion" rows="2" maxlength="300" required
                        class="form-control @error('direccion') is-invalid @enderror">{{ old('direccion') }}</textarea>
                    @error('direccion')
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
                <button type="reset" id="btnLimpiar" class="btn btn-warning">
                    <i class="bi bi-trash"></i> Limpiar
                </button>
                <a href="{{ route('pacientes.index') }}" class="btn btn-success">
                    <i class="bi bi-arrow-left"></i> Regresar
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Script de limpieza -->
<script>
document.getElementById('btnLimpiar').addEventListener('click', () => {
    const form = document.getElementById('formPaciente');

    form.reset();

    form.querySelectorAll('input, select, textarea').forEach(el => {
        el.classList.remove('is-invalid', 'is-valid');
    });

    form.querySelectorAll('.invalid-feedback').forEach(el => {
        el.textContent = '';
        el.style.display = 'none';
    });
});
</script>

@endsection
