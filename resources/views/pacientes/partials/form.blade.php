@extends('layouts.app')

@section('content')

    <!-- Barra de navegación superior -->
    <div class="header d-flex justify-content-between align-items-center px-3 py-2" style="background-color: #007BFF; position: sticky; top: 0; z-index: 1030;">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek"
                 style="height: 40px; width: auto; margin-right: 6px;">
            <span class="fw-bold text-white" style="font-size: 1.5rem;">Clinitek</span>
        </div>
        <div class="d-flex gap-3 flex-wrap">
            <a href="{{ route('puestos.create') }}" class="text-decoration-none text-white fw-semibold">Crear puesto</a>
            <a href="{{ route('empleado.create') }}" class="text-decoration-none text-white fw-semibold">Registrar empleado</a>
            <a href="{{ route('medicos.create') }}" class="text-decoration-none text-white fw-semibold">Registrar médico</a>
        </div>
    </div>

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
            <h2 class="fw-bold text-black mb-0">Editar Paciente</h2>
        </div>

        <form id="formPaciente" action="{{ route('pacientes.update', $paciente->id) }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <h5 class="mb-3 text-dark fw-bold">Datos básicos y contacto</h5>

            <!-- Fila 1 -->

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="nombre" class="form-label">Nombre(s): <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="nombre" maxlength="50" required
                                   pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$"
                                   class="form-control @error('nombre') is-invalid @enderror"
                                   value="{{ old('nombre', $paciente->nombre) }}">
                            @error('nombre') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="apellidos" class="form-label">Apellidos: <span class="text-danger">*</span></label>
                            <input type="text" name="apellidos" id="apellidos" maxlength="50" required
                                   pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$"
                                   class="form-control @error('apellidos') is-invalid @enderror"
                                   value="{{ old('apellidos', $paciente->apellidos) }}">
                            @error('apellidos') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="identidad" class="form-label">Identidad: <span class="text-danger">*</span></label>
                            <input type="text" name="identidad" id="identidad" maxlength="13"
                                   pattern="^(0[1-9]|1[0-8])(0[1-9]|1[0-9]|2[0-8])[0-9]{9}$"
                                   required
                                   class="form-control @error('identidad') is-invalid @enderror"
                                   value="{{ old('identidad', $paciente->identidad) }}">
                            @error('identidad') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento: <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required
                                   class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                                   value="{{ old('fecha_nacimiento', $paciente->fecha_nacimiento ? \Carbon\Carbon::parse($paciente->fecha_nacimiento)->format('Y-m-d') : '') }}">
                            @error('fecha_nacimiento') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="telefono" class="form-label">Teléfono: <span class="text-danger">*</span></label>
                            <input type="tel" name="telefono" id="telefono" maxlength="8"
                                   pattern="^[2389][0-9]{7}$"
                                   required
                                   class="form-control @error('telefono') is-invalid @enderror"
                                   value="{{ old('telefono', $paciente->telefono) }}">
                            @error('telefono') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="correo" class="form-label">Correo electrónico: <span class="text-danger">*</span></label>
                            <input type="email" name="correo" id="correo" maxlength="50" required
                                   class="form-control @error('correo') is-invalid @enderror"
                                   value="{{ old('correo', $paciente->correo) }}">
                            @error('correo') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="tipo_sangre" class="form-label">Tipo de Sangre (opcional):</label>
                            <select name="tipo_sangre" id="tipo_sangre" class="form-select @error('tipo_sangre') is-invalid @enderror">
                                <option value="">Seleccione...</option>
                                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $tipo)
                                    <option value="{{ $tipo }}" {{ old('tipo_sangre', $paciente->tipo_sangre) == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                                @endforeach
                            </select>
                            @error('tipo_sangre') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="genero" class="form-label">Género: <span class="text-danger">*</span></label>
                            <select name="genero" id="genero" required class="form-select @error('genero') is-invalid @enderror">
                                <option value="">Seleccione...</option>
                                <option value="Femenino" {{ old('genero', $paciente->genero) == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                <option value="Masculino" {{ old('genero', $paciente->genero) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="Otro" {{ old('genero', $paciente->genero) == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('genero') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="direccion" class="form-label">Dirección: <span class="text-danger">*</span></label>
                            <textarea name="direccion" id="direccion" rows="2" maxlength="300" required
                                      class="form-control @error('direccion') is-invalid @enderror"
                                      style="resize: vertical;">{{ old('direccion', $paciente->direccion) }}</textarea>
                            @error('direccion') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Sección clínica -->
                    <h5 class="mt-4 mb-3 text-dark fw-bold">Datos clínicos</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="padecimientos" class="form-label">Padecimientos:</label>
                            <textarea name="padecimientos" id="padecimientos" rows="2" maxlength="200" required
                                      class="form-control @error('padecimientos') is-invalid @enderror">{{ old('padecimientos', $paciente->padecimientos) }}</textarea>
                            @error('padecimientos') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="medicamentos" class="form-label">Medicamentos que consume:</label>
                            <textarea name="medicamentos" id="medicamentos" rows="2" maxlength="200" required
                                      class="form-control @error('medicamentos') is-invalid @enderror">{{ old('medicamentos', $paciente->medicamentos) }}</textarea>
                            @error('medicamentos') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="historial_clinico" class="form-label">Historial clínico:</label>
                            <textarea name="historial_clinico" id="historial_clinico" rows="2" maxlength="200" required
                                      class="form-control @error('historial_clinico') is-invalid @enderror">{{ old('historial_clinico', $paciente->historial_clinico) }}</textarea>
                            @error('historial_clinico') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="alergias" class="form-label">Alergias conocidas:</label>
                            <textarea name="alergias" id="alergias" rows="2" maxlength="200" required
                                      class="form-control @error('alergias') is-invalid @enderror">{{ old('alergias', $paciente->alergias) }}</textarea>
                            @error('alergias') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="historial_quirurgico" class="form-label">Historial quirúrgico (opcional):</label>
                        <textarea name="historial_quirurgico" id="historial_quirurgico" rows="2" maxlength="200"
                                  class="form-control @error('historial_quirurgico') is-invalid @enderror">{{ old('historial_quirurgico', $paciente->historial_quirurgico) }}</textarea>
                        @error('historial_quirurgico') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
            <!-- Botones centrados -->
            <div class="d-flex justify-content-center gap-3 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Guardar Cambios
                </button>
                <button type="button" id="btnRestablecer" class="btn btn-warning">
                    <i class="bi bi-arrow-counterclockwise"></i> Restablecer
                </button>
                <a href="{{ route('pacientes.index') }}" class="btn btn-success">
                    <i class="bi bi-arrow-left"></i> Regresar
                </a>
            </div>
        </form>
    </div>

    <!-- Script de restablecer -->
    <script>
        document.getElementById('btnRestablecer')?.addEventListener('click', () => {
            const form = document.getElementById('formPaciente');
            form.reset();
            form.querySelectorAll('input, select, textarea').forEach(el => {
                el.classList.remove('is-invalid', 'is-valid');
            });
            form.querySelectorAll('.invalid-feedback').forEach(el => {
                el.textContent = '';
                el.style.display = 'none';
            });
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>

    <script>
        document.querySelectorAll('input[name="nombre"], input[name="apellidos"]').forEach(input => {
            input.addEventListener('keypress', function(e) {
                const char = String.fromCharCode(e.which);
                const regex = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]$/;
                if (!regex.test(char)) {
                    e.preventDefault();
                }
            });
        });
        document.querySelectorAll('input[name="identidad"], input[name="telefono"]').forEach(input => {
            input.addEventListener('keypress', function(e) {
                const char = String.fromCharCode(e.which);
                const regex = /^[0-9]$/;
                if (!regex.test(char)) {
                    e.preventDefault();
                }
            });
        });
    </script>
    <script>
        document.querySelectorAll('input, textarea, select').forEach(input => {
            input.addEventListener('input', () => {
                // Quitar clase de error y agregar clase válida si hay contenido
                input.classList.remove('is-invalid');
                input.classList.toggle('is-valid', input.value.trim() !== '');

                // Buscar mensajes de error directamente después del input
                let next = input.nextElementSibling;
                while (next && next.classList.contains('invalid-feedback')) {
                    next.remove(); // ⬅️ Elimina el div del DOM completamente
                    next = input.nextElementSibling;
                }
            });
        });
    </script>

@endsection
