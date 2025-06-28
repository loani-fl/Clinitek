@extends('layouts.app')

@section('content')

    <!-- Barra de navegación superior -->
    <div class="header d-flex justify-content-between align-items-center px-3 py-2"
         style="background-color: #007BFF; position: sticky; top: 0; z-index: 1030;">
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

    <!-- Estilo del formulario -->
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
            background-image: url('{{ asset('images/logo2.jpg') }}');
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

        <div class="mb-4 text-center" style="border-bottom: 3px solid #007BFF;">
            <h2 class="fw-bold text-black mb-0">Editar Médico</h2>
        </div>

        {{-- Botón para cambiar estado --}}
        <form id="estadoForm" action="{{ route('medicos.toggleEstado', $medico->id) }}" method="POST" class="d-inline mb-3">
            @csrf
            @method('PATCH')

            <button type="submit" id="estadoBtn"
                    class="btn btn-sm {{ $medico->estado ? 'btn-outline-danger' : 'btn-outline-success' }}"
                    title="{{ $medico->estado ? 'Desactivar médico' : 'Activar médico' }}">
                <i id="iconoEstado" class="bi {{ $medico->estado ? 'bi-person-dash' : 'bi-person-check' }}"></i>
                <span id="textoEstado">{{ $medico->estado ? 'Desactivar Médico' : 'Activar Médico' }}</span>
            </button>
        </form>

        <!-- Formulario -->
        <form id="formMedico"
              action="{{ route('medicos.update', $medico->id) }}"
              method="POST"
              class="needs-validation"
              novalidate
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">

                <div class="col-md-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" maxlength="50"
                           class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre', $medico->nombre) }}" required>
                    <div class="invalid-feedback">Completa este dato</div>
                    @error('nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Apellidos</label>
                    <input type="text" name="apellidos" maxlength="50"
                           class="form-control @error('apellidos') is-invalid @enderror"
                           value="{{ old('apellidos', $medico->apellidos) }}" required>
                    <div class="invalid-feedback">Completa este dato</div>
                    @error('apellidos')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Especialidad</label>
                    <select name="especialidad" id="especialidad" class="form-select @error('especialidad') is-invalid @enderror" required>
                        <option value="">Seleccionar especialidad</option>
                        @php
                            $especialidades = ['Cardiología', 'Neurología', 'Pediatría', 'Dermatología', 'Psiquiatría', 'Radiología'];
                        @endphp
                        @foreach ($especialidades as $esp)
                            <option value="{{ $esp }}" {{ old('especialidad', $medico->especialidad) == $esp ? 'selected' : '' }}>
                                {{ $esp }}
                            </option>
                        @endforeach
                    </select>
                    @error('especialidad')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" maxlength="8" pattern="[983][0-9]{7}"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                           class="form-control @error('telefono') is-invalid @enderror"
                           value="{{ old('telefono', $medico->telefono) }}" required>
                    <div class="invalid-feedback"></div>
                    @error('telefono')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Correo</label>
                    <input type="email" name="correo" maxlength="30"
                           class="form-control @error('correo') is-invalid @enderror"
                           value="{{ old('correo', $medico->correo) }}" required>
                    @error('correo')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Identidad</label>
                    <input type="text" name="numero_identidad" maxlength="13"
                           class="form-control @error('numero_identidad') is-invalid @enderror"
                           value="{{ old('numero_identidad', $medico->numero_identidad) }}" required>
                    <div class="invalid-feedback">Completa este dato</div>
                    @error('numero_identidad')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Salario</label>
                    <input type="text" id="salario_mostrado" class="form-control" readonly>

                    <input type="hidden" name="salario" id="salario_real"
                           value="{{ old('salario', $medico->salario) }}">
                    @error('salario')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento"
                           class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                           value="{{ old('fecha_nacimiento', $medico->fecha_nacimiento) }}"
                           required min="1950-01-01" max="2005-12-31">
                    <div class="invalid-feedback">Completa este dato</div>
                    @error('fecha_nacimiento')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @php
                    $minFecha = \Carbon\Carbon::now()->subMonth()->format('Y-m-d');
                    $maxFecha = \Carbon\Carbon::now()->addMonth()->format('Y-m-d');
                @endphp

                <div class="col-md-3">
                    <label class="form-label">Fecha de Ingreso</label>
                    <input type="date" name="fecha_ingreso" id="fecha_ingreso"
                           class="form-control @error('fecha_ingreso') is-invalid @enderror"
                           value="{{ old('fecha_ingreso', $medico->fecha_ingreso) }}"
                           min="{{ $minFecha }}" max="{{ $maxFecha }}" required>
                    <div class="invalid-feedback">Completa este dato</div>
                    @error('fecha_ingreso')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Género</label>
                    <select name="genero" class="form-select @error('genero') is-invalid @enderror" required>
                        <option value="">Seleccionar</option>
                        <option value="Masculino" {{ old('genero', $medico->genero) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                        <option value="Femenino" {{ old('genero', $medico->genero) == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                        <option value="Otro" {{ old('genero', $medico->genero) == 'Otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                    @error('genero')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Dirección</label>
                    <textarea name="direccion" maxlength="150" rows="3"
                              class="form-control @error('direccion') is-invalid @enderror"
                              required>{{ old('direccion', $medico->direccion) }}</textarea>
                    <div class="invalid-feedback">Completa este dato</div>
                    @error('direccion')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-6">
                    <label class="form-label">Observaciones</label>
                    <textarea name="observaciones" maxlength="100" rows="3"
                              class="form-control @error('observaciones') is-invalid @enderror">{{ old('observaciones', $medico->observaciones) }}</textarea>
                    @error('observaciones')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    {{-- Foto actual --}}
                    @if(!empty($medico->foto) && file_exists(storage_path('app/public/' . $medico->foto)))
                        <img src="{{ asset('storage/' . $medico->foto) }}"
                             alt="Foto actual del médico"
                             class="shadow-sm"
                             style="width: 120px; height: 120px; object-fit: cover; border-radius: 12px; border: 2px solid #0d6efd; margin-bottom: 0.5rem;">
                    @else
                        <img src="{{ asset('images/default-user.png') }}"
                             alt="Foto por defecto"
                             class="shadow-sm"
                             style="width: 120px; height: 120px; object-fit: cover; border-radius: 12px; border: 2px solid #0d6efd; margin-bottom: 0.5rem;">
                    @endif

                    {{-- Input para subir nueva foto --}}
                    <label for="foto" class="form-label">Foto:</label>
                    <input type="file" name="foto" id="foto"
                           class="form-control form-control-sm @error('foto') is-invalid @enderror"
                           accept="image/*">
                    @error('foto')
                    <div class="invalid-feedback">Suba una imagen válida. Opcional.</div>
                    @enderror
                    <div class="form-text">Opcional. Formato: JPG, PNG.</div>
                </div>



            </div>

            <div class="d-flex justify-content-center gap-3 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Actualizar
                </button>

                <button type="button" id="btnRestablecer" class="btn btn-warning">
                    <i class="bi bi-arrow-counterclockwise"></i> Restablecer
                </button>

                <a href="{{ route('medicos.index') }}" class="btn btn-success">
                    <i class="bi bi-arrow-left"></i> Regresar
                </a>
            </div>

        </form>
    </div>

    <!-- Script para restablecer formulario -->
    <script>
        document.getElementById('btnRestablecer').addEventListener('click', () => {
            const form = document.getElementById('formMedico');
            form.reset();

            // Quitar clases de validación de bootstrap
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

    <!-- Script AJAX para toggle estado (opcional) -->
    <script>
        document.getElementById('estadoForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const form = this;
            const btn = document.getElementById('estadoBtn');
            const icon = document.getElementById('iconoEstado');
            const texto = document.getElementById('textoEstado');

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: new URLSearchParams(new FormData(form))
            })
                .then(res => res.json())
                .then(data => {
                    if (data.estado) {
                        btn.classList.remove('btn-outline-success');
                        btn.classList.add('btn-outline-danger');
                        icon.className = 'bi bi-person-dash';
                        texto.textContent = 'Desactivar Médico';
                    } else {
                        btn.classList.remove('btn-outline-danger');
                        btn.classList.add('btn-outline-success');
                        icon.className = 'bi bi-person-check';
                        texto.textContent = 'Activar Médico';
                    }
                })
                .catch(err => console.error('Error al cambiar estado:', err));
        });
    </script>

    <!-- Script para manejar el salario mostrado -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
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

            function formatearSalario(valor) {
                return 'L ' + valor.toLocaleString('es-HN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            function actualizarSalario() {
                const seleccion = especialidadSelect.value;
                if (salariosPorEspecialidad.hasOwnProperty(seleccion)) {
                    const salario = salariosPorEspecialidad[seleccion];
                    salarioReal.value = salario;
                    salarioMostrado.value = formatearSalario(salario);
                } else {
                    salarioReal.value = '';
                    salarioMostrado.value = '';
                }
            }

            especialidadSelect.addEventListener('change', actualizarSalario);

            // Al cargar, mostrar salario actual si hay
            if (especialidadSelect.value) {
                actualizarSalario();
            } else if (salarioReal.value) {
                salarioMostrado.value = formatearSalario(parseFloat(salarioReal.value));
            }
        });
    </script>

@endsection
