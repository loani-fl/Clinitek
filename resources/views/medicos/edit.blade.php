@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="mb-3">
            <form id="estadoForm" action="{{ route('medicos.toggleEstado', $medico->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit"
                        id="estadoBtn"
                        class="btn {{ $medico->estado ? 'btn-outline-danger' : 'btn-outline-success' }}"
                        title="{{ $medico->estado ? 'Desactivar médico' : 'Activar médico' }}">
                    <i id="iconoEstado" class="bi {{ $medico->estado ? 'bi-person-dash' : 'bi-person-check' }}"></i>
                    <span id="textoEstado">{{ $medico->estado ? 'Desactivar Médico' : 'Activar Médico' }}</span>
                </button>
            </form>

            <div class="card shadow-sm rounded-3">
                <div class="card shadow-sm rounded-3">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Editar Médico</h4>
                        <a href="{{ route('medicos.index') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-arrow-left"></i> Volver a la lista
                        </a>
                    </div>

                    <div class="card-body">

                        {{-- Formulario de edición --}}
                        <form class="needs-validation" novalidate action="{{ route('medicos.update', $medico->id) }}" method="POST" enctype="multipart/form-data">


                        @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" name="nombre" maxlength="50"
                                           class="form-control @error('nombre') is-invalid @enderror"
                                           value="{{ old('nombre', $medico->nombre) }}" required  value="{{ old('nombre', $medico->nombre) }}">
                                    <div class="invalid-feedback">Completa este dato</div>
                                    @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Apellidos</label>
                                    <input type="text" name="apellidos" maxlength="50"
                                           class="form-control @error('apellidos') is-invalid @enderror"
                                           value="{{ old('apellidos', $medico->apellidos) }}" required value="{{ old('nombre', $medico->nombre) }}">
                                    <div class="invalid-feedback">Completa este dato</div>
                                    @error('apellidos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Especialidad</label>
                                    <select name="especialidad" class="form-select @error('especialidad') is-invalid @enderror" required value="{{ old('nombre', $medico->nombre) }}">
                                        <div class="invalid-feedback">Completa este dato</div>
                                        <option value="">Seleccionar especialidad</option>
                                        @php
                                            $especialidades = [
                                                'Alergología', 'Anestesiología', 'Cardiología', 'Cirugía General', 'Cirugía Plástica',
                                                'Dermatología', 'Endocrinología', 'Gastroenterología', 'Geriatría', 'Ginecología y Obstetricia',
                                                'Hematología', 'Infectología', 'Medicina Familiar', 'Medicina Interna', 'Nefrología',
                                                'Neonatología', 'Neumología', 'Neurología', 'Nutriología', 'Oftalmología',
                                                'Oncología', 'Ortopedia y Traumatología', 'Otorrinolaringología', 'Patología', 'Pediatría',
                                                'Psiquiatría', 'Radiología', 'Reumatología', 'Urología', 'Medicina del Deporte'
                                            ];
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
                                <div class="col-md-4">
                                    <label class="form-label">Teléfono</label>
                                    <input type="text"
                                           name="telefono"
                                           maxlength="8"
                                           pattern="[983][0-9]{7}"
                                           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                           class="form-control @error('telefono') is-invalid @enderror"
                                           value="{{ old('telefono', $medico->telefono) }}"
                                           required>
                                    <div class="invalid-feedback">Ingresa un número válido que comience con 9, 8 o 3</div>
                                    @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="col-md-4">
                                    <label class="form-label">Correo</label>
                                    <input type="email" name="correo" maxlength="50"
                                           class="form-control @error('correo') is-invalid @enderror"
                                           value="{{ old('correo', $medico->correo) }}" required >
                                    @error('correo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Identidad</label>
                                    <input type="text" name="identidad" maxlength="13"
                                           class="form-control @error('identidad') is-invalid @enderror"
                                           value="{{ old('identidad', $medico->identidad) }}" required value="{{ old('nombre', $medico->nombre) }}">
                                    <div class="invalid-feedback">Completa este dato</div>
                                    @error('identidad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Salario</label>
                                    <input type="number" name="salario" step="0.01" min="0" max="99999.99"
                                           class="form-control @error('salario') is-invalid @enderror"
                                           value="{{ old('salario', $medico->salario) }}"
                                           id="salario" oninput="validarSalario(this)">
                                    @error('salario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Fecha de Nacimiento</label>
                                    <input type="date" name="fecha_nacimiento"
                                           class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                                           value="{{ old('fecha_nacimiento', $medico->fecha_nacimiento) }}" required value="{{ old('nombre', $medico->nombre) }}">
                                    <div class="invalid-feedback">Completa este dato</div>
                                    @error('fecha_nacimiento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Fecha de Ingreso</label>
                                    <input type="date" name="fecha_ingreso"
                                           class="form-control @error('fecha_ingreso') is-invalid @enderror"
                                           value="{{ old('fecha_ingreso', $medico->fecha_ingreso) }}" required value="{{ old('nombre', $medico->nombre) }}">
                                    <div class="invalid-feedback">Completa este dato</div>
                                    @error('fecha_ingreso')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Género</label>
                                    <select name="genero" class="form-select @error('genero') is-invalid @enderror" required  value="{{ old('nombre', $medico->nombre) }}">
                                        <div class="invalid-feedback">Completa este dato</div>
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
                                    <label class="form-label">Observaciones</label>
                                    <textarea name="observaciones" maxlength="100" rows="3"
                                              class="form-control @error('observaciones') is-invalid @enderror">{{ old('observaciones', $medico->observaciones) }}</textarea>
                                    @error('observaciones')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label for="foto" class="form-label form-label-sm mb-1">Foto actual</label><br>
                                @if ($medico->foto)
                                    <img src="{{ asset('storage/' . $medico->foto) }}" alt="Foto actual"
                                         class="img-thumbnail mb-1" style="width: 100px; height: 100px; object-fit: cover;">
                                @else
                                    <p class="text-muted mb-1">No hay foto</p>
                                @endif

                                <label for="foto" class="form-label form-label-sm mt-1">Cambiar foto</label>
                                <input type="file" name="foto" id="foto"
                                       class="form-control form-control-sm @error('foto') is-invalid @enderror">
                                @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Guardar cambios
                        </button>

                            <button type="reset" class="btn btn-secondary">Restablecer</button>


                        </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function confirmarCancelacion() {
            if (confirm('¿Deseas cancelar la actualización del médico?')) {
                window.location.href = "{{ route('medicos.index') }}";
            }
        }
    </script>


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
                                // Cambiar clases y texto según el nuevo estado
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


                <script>
                    (() => {
                        'use strict';
                        const forms = document.querySelectorAll('.needs-validation');

                        Array.from(forms).forEach(form => {
                            form.addEventListener('submit', event => {
                                if (!form.checkValidity()) {
                                    event.preventDefault();
                                    event.stopPropagation();
                                }

                                form.classList.add('was-validated');
                            }, false);
                        });
                    })();
                </script>
                <script>
                    (() => {
                        'use strict';
                        const forms = document.querySelectorAll('.needs-validation');

                        Array.from(forms).forEach(form => {
                            form.addEventListener('submit', event => {
                                if (!form.checkValidity()) {
                                    event.preventDefault();
                                    event.stopPropagation();

                                    // Buscar el primer campo inválido
                                    const firstInvalidField = form.querySelector(':invalid');
                                    if (firstInvalidField) {
                                        firstInvalidField.scrollIntoView({
                                            behavior: 'smooth',
                                            block: 'center'
                                        });
                                        firstInvalidField.focus();
                                    }
                                }

                                form.classList.add('was-validated');
                            }, false);
                        });
                    })();
                </script>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const correoField = document.querySelector('input[name="correo"]');

                        if (correoField) {
                            correoField.addEventListener('invalid', function(e) {
                                e.preventDefault();
                                if (correoField.validity.typeMismatch) {
                                    correoField.setCustomValidity('El correo debe tener un formato válido, incluyendo "@" y "."');
                                } else {
                                    correoField.setCustomValidity('');
                                }
                                correoField.reportValidity();
                            });

                            correoField.addEventListener('input', function() {
                                correoField.setCustomValidity('');
                            });
                        }
                    });
                </script>
                <script>
                    function validarSalario(input) {
                        let valor = input.value;

                        // Forzamos a máximo 5 dígitos enteros y 2 decimales
                        const regex = /^(\d{0,5})(\.\d{0,2})?$/;

                        if (!regex.test(valor)) {
                            valor = valor.slice(0, -1); // elimina último caracter si no cumple
                        }

                        input.value = valor;
                    }
                </script>

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const correoInput = document.querySelector('input[name="correo"]');
                        correoInput.addEventListener("invalid", function(event) {
                            event.target.setCustomValidity("Completa este dato");
                        });

                        correoInput.addEventListener("input", function(event) {
                            event.target.setCustomValidity(""); // Quita el mensaje cuando empieza a escribir
                        });
                    });
                </script>


    @endpush


