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
        .text-danger {
            font-weight: 600;
            font-size: 0.875rem;
        }
    </style>

    <div class="custom-card">
        <div class="mb-4 text-center" style="border-bottom: 3px solid #007BFF;">
            <h2 class="fw-bold text-black mb-0">Editar Farmacia</h2>
        </div>

        <form id="editFarmaciaForm" action="{{ route('farmacias.update', $farmacia->id) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')

            <!-- Primera fila: Nombre, Teléfono, Descuento -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="nombre" class="form-label">Nombre: <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" id="nombre"
                           class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre', $farmacia->nombre) }}"
                           data-original="{{ $farmacia->nombre }}"
                           required maxlength="50">
                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label for="telefono" class="form-label">Teléfono: <span class="text-danger">*</span></label>
                    <input type="tel" name="telefono" id="telefono"
                           class="form-control @error('telefono') is-invalid @enderror"
                           value="{{ old('telefono', $farmacia->telefono) }}"
                           data-original="{{ $farmacia->telefono }}"
                           maxlength="8" pattern="^\d{8}$">
                    @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label for="descuento" class="form-label">Descuento (%):</label>
                    <input
                        type="number"
                        name="descuento"
                        id="descuento"
                        class="form-control @error('descuento') is-invalid @enderror"
                        value="{{ old('descuento', $farmacia->descuento) }}"
                        placeholder="Ej: 10.5"
                        min="1"
                        max="100"
                        step="0.1"
                        oninput="
            let val = this.value;
            // Limita máximo 2 dígitos antes del punto y 1 decimal
            val = val.replace(/^(\d{0,2})(\.\d?)?.*$/,'$1$2');
            // Bloquea valores mayores a 100
            if(val !== '' && parseFloat(val) > 100) val = '100';
            // Bloquea valores menores a 1
            if(val !== '' && parseFloat(val) < 1) val = '1';
            this.value = val;
        ">
                    @error('descuento')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <!-- Segunda fila: Página web, Departamento, Ciudad -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="pagina_web" class="form-label">Página web (Opcional):</label>
                    <input type="url" name="pagina_web" id="pagina_web"
                           class="form-control @error('pagina_web') is-invalid @enderror"
                           value="{{ old('pagina_web', $farmacia->pagina_web) }}"
                           data-original="{{ $farmacia->pagina_web }}"
                           placeholder="https://ejemplo.com">
                    @error('pagina_web')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label for="departamento" class="form-label">Departamento:</label>
                    <select name="departamento" id="departamento" class="form-control @error('departamento') is-invalid @enderror" data-original="{{ $farmacia->departamento }}">
                        <option value="">Seleccione un departamento</option>
                        @foreach($departamento as $dep)
                            <option value="{{ $dep }}" {{ old('departamento', $farmacia->departamento) == $dep ? 'selected' : '' }}>
                                {{ $dep }}
                            </option>
                        @endforeach
                    </select>
                    @error('departamento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label for="ciudad" class="form-label">Ciudad:</label>
                    <select name="ciudad" id="ciudad" class="form-control @error('ciudad') is-invalid @enderror" data-original="{{ $farmacia->ciudad }}">
                        <option value="">Seleccione una ciudad</option>
                    </select>
                    @error('ciudad')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <!-- Tercera fila: Dirección y Descripción -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="direccion" class="form-label">Dirección específica:</label>
                    <textarea name="direccion" id="direccion" rows="2"
                              maxlength="255"
                              class="form-control @error('direccion') is-invalid @enderror"
                              data-original="{{ $farmacia->direccion }}"
                              placeholder="Ej: Barrio El Centro, Casa #45, frente a la escuela">{{ old('direccion', $farmacia->direccion) }}</textarea>
                    @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label for="descripcion" class="form-label">Descripción:</label>
                    <textarea name="descripcion" id="descripcion" rows="2"
                              maxlength="255"
                              class="form-control @error('descripcion') is-invalid @enderror"
                              data-original="{{ $farmacia->descripcion }}"
                              placeholder="Descripción breve de la farmacia">{{ old('descripcion', $farmacia->descripcion) }}</textarea>
                    @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>


            <!-- Foto -->
            <div class="mb-3" style="max-width: 400px;">
                <label for="foto" class="form-label">Foto:</label>
                <div class="mb-2">
                    @php
                        // Mostrar foto temporal de sesión si existe, si no la original
                        $fotoMostrar = session('foto_temporal')
                            ? asset('storage/' . session('foto_temporal'))
                            : ($farmacia->foto ? asset('storage/' . $farmacia->foto) : '');
                    @endphp

                    <img src="{{ $fotoMostrar }}"
                         alt="Foto actual"
                         class="img-thumbnail"
                         id="fotoPreview"
                         data-original="{{ $farmacia->foto ? asset('storage/' . $farmacia->foto) : '' }}"
                         style="max-width: 120px; {{ $fotoMostrar ? '' : 'display:none;' }}">
                </div>
                <input type="file" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror">
                <input type="hidden" name="restablecer_foto" id="restablecer_foto" value="0">
                @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-center gap-3 mt-4">
                <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Guardar Cambios</button>
                <button type="button" class="btn btn-secondary" id="restablecerBtn">Restablecer</button>

                <a href="{{ route('farmacias.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        // Validaciones de nombre y teléfono
        document.getElementById("nombre").addEventListener("input", function() {
            this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s\-]/g, '');
        });
        document.getElementById("telefono").addEventListener("input", function() {
            this.value = this.value.replace(/\D/g, '').slice(0, 8);
        });

        // Manejo dinámico de ciudades
        const ciudadData = @json($ciudad);
        const departamentoSelect = document.getElementById('departamento');
        const ciudadSelect = document.getElementById('ciudad');

        function actualizarCiudades(selectedDepartamento, selectedCiudad = null) {
            ciudadSelect.innerHTML = '<option value="">Seleccione una ciudad</option>';
            if(ciudadData[selectedDepartamento]) {
                ciudadData[selectedDepartamento].forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c;
                    opt.textContent = c;
                    if(selectedCiudad && selectedCiudad === c) opt.selected = true;
                    ciudadSelect.appendChild(opt);
                });
            }
        }

        actualizarCiudades("{{ old('departamento', $farmacia->departamento) }}", "{{ old('ciudad', $farmacia->ciudad) }}");
        departamentoSelect.addEventListener('change', function() {
            actualizarCiudades(this.value);
        });


        // Preview de la foto nueva al instante
        const fotoInput = document.getElementById('foto');
        const fotoPreview = document.getElementById('fotoPreview');

        fotoInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if(file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    fotoPreview.src = e.target.result;
                    fotoPreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                fotoPreview.src = fotoPreview.getAttribute('data-original');
                fotoPreview.style.display = fotoPreview.src ? 'block' : 'none';
            }
        });

        // Botón Restablecer
        document.getElementById('restablecerBtn').addEventListener('click', function() {
            const form = document.getElementById('editFarmaciaForm');

            form.querySelectorAll('input, textarea, select').forEach(input => {
                const original = input.getAttribute('data-original');
                if(original !== null) {
                    if(input.tagName === 'SELECT') {
                        input.value = original;
                        input.dispatchEvent(new Event('change'));
                    } else {
                        input.value = original;
                    }
                }
                if(input.type === 'file') input.value = '';
                input.classList.remove('is-invalid');
            });

            // Restaurar la foto original de la base de datos
            fotoPreview.src = fotoPreview.getAttribute('data-original');
            fotoPreview.style.display = fotoPreview.src ? 'block' : 'none';
            fotoInput.value = '';

            // Eliminar mensajes de validación
            form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

            // Foco en el primer campo editable
            setTimeout(() => {
                const primerCampo = form.querySelector('input:not([type=file]), textarea, select');
                if(primerCampo) primerCampo.focus();
            }, 10);
        });
    </script>

    <script>
        document.getElementById('restablecerBtn').addEventListener('click', function () {
            const fotoPreview = document.getElementById('fotoPreview');
            const fotoInput = document.getElementById('foto');

            // Restaurar la foto original
            fotoPreview.src = fotoPreview.getAttribute('data-original');
            fotoPreview.style.display = fotoPreview.src ? 'block' : 'none';

            // Resetear input
            fotoInput.value = '';

            // Marcar que se debe restablecer en el backend
            document.getElementById('restablecer_foto').value = "1";
        });
    </script>
    <script>
        // Bloquear caracteres no permitidos en Dirección
        document.getElementById('direccion').addEventListener('keydown', function(e) {
            const allowedKeys = ['Backspace', 'Tab', 'ArrowLeft', 'ArrowRight', 'Delete', 'Home', 'End'];
            if (allowedKeys.includes(e.key)) return;

            const regex = /^[a-zA-Z0-9\s.,#]$/; // Solo letras, números, espacios, punto, coma y numeral
            if (!regex.test(e.key)) {
                e.preventDefault(); // Bloquea cualquier otra tecla
            }
        });

        // Bloquear caracteres no permitidos en Descripción
        document.getElementById('descripcion').addEventListener('keydown', function(e) {
            const allowedKeys = ['Backspace', 'Tab', 'ArrowLeft', 'ArrowRight', 'Delete', 'Home', 'End'];
            if (allowedKeys.includes(e.key)) return;

            const regex = /^[a-zA-Z0-9\s.,%]$/; // Solo letras, números, espacios, punto, coma y %
            if (!regex.test(e.key)) {
                e.preventDefault(); // Bloquea cualquier otra tecla
            }
        });

    </script>

@endsection
