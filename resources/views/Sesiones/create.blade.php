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

        #previewContainer {
            display: none;
            margin-bottom: 0.5rem;
            text-align: center; /* Centrar la imagen */
        }

        #previewImage {
            max-width: 250px; /* Más pequeña */
            max-height: 150px;
            border-radius: 5px;
            object-fit: cover;
            box-shadow: 0 0 5px rgba(0,0,0,0.2);
        }

    </style>

    <div class="custom-card">
        <div class="mb-4 text-center" style="border-bottom: 3px solid #007BFF;">
            <h2 class="fw-bold text-black mb-0">Registro de resultados de examenes psicometricos </h2>
        </div>

        <form id="formSesion" action="{{ route('sesiones.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

            {{-- Sección Paciente --}}
            <div class="card mb-3 p-3 shadow-sm border">
                <h5 class="fw-bold mb-3">Información del paciente</h5>

                <div class="row g-3 align-items-end">
                    <!-- Paciente -->
                    <div class="col-md-6">
                        <label for="paciente_id" class="form-label">Paciente <span class="text-danger">*</span></label>
                        <select name="paciente_id" id="paciente_id"
                                class="form-select @error('paciente_id') is-invalid @enderror" required>
                            <option value="">-- Selecciona --</option>
                            @foreach($pacientes as $p)
                                <option value="{{ $p->id }}"
                                        {{ old('paciente_id') == $p->id ? 'selected' : '' }}
                                        data-nacimiento="{{ $p->fecha_nacimiento }}"
                                        data-genero="{{ $p->genero }}"
                                        data-telefono="{{ $p->telefono }}">
                                    {{ $p->nombre }} {{ $p->apellidos }}
                                </option>
                            @endforeach
                        </select>
                        @error('paciente_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label>Edad</label>
                        <input type="text" id="edad" class="form-control bg-light" readonly>
                    </div>
                    <div class="col-md-2">
                        <label>Género</label>
                        <input type="text" id="genero" class="form-control bg-light" readonly>
                    </div>
                    <div class="col-md-2">
                        <label>Teléfono</label>
                        <input type="text" id="telefono" class="form-control bg-light" readonly>
                    </div>
                </div>
            </div>

            {{-- Sección Médico y Fecha/Hora --}}
            <div class="card mb-3 p-3 shadow-sm border">
                <h5 class="fw-bold mb-3">Médico y horario</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="medico_id" class="form-label">Médico <span class="text-danger">*</span></label>
                        <select name="medico_id" id="medico_id" class="form-select @error('medico_id') is-invalid @enderror" required>
                            <option value="">-- Selecciona --</option>
                            @foreach($medicos as $m)
                                <option value="{{ $m->id }}" {{ old('medico_id') == $m->id ? 'selected' : '' }}>
                                    {{ $m->nombre }} {{ $m->apellidos }}
                                </option>
                            @endforeach
                        </select>
                        @error('medico_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="fecha" class="form-label">Fecha <span class="text-danger">*</span></label>
                        <input
                            type="date"
                            id="fecha"
                            name="fecha"
                            class="form-control @error('fecha') is-invalid @enderror"
                            value="{{ old('fecha', \Carbon\Carbon::now()->format('Y-m-d')) }}"
                            required
                        >
                        @error('fecha')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label for="hora_inicio" class="form-label">Hora inicio <span class="text-danger">*</span></label>
                        <input type="time" name="hora_inicio" id="hora_inicio"
                               class="form-control @error('hora_inicio') is-invalid @enderror"
                               value="{{ old('hora_inicio') }}" required>
                        @error('hora_inicio')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label for="hora_fin" class="form-label">Hora final <span class="text-danger">*</span></label>
                        <input type="time" name="hora_fin" id="hora_fin"
                               class="form-control @error('hora_fin') is-invalid @enderror"
                               value="{{ old('hora_fin') }}" required>
                        @error('hora_fin')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Sección Motivo y Examen --}}
            <div class="card mb-3 p-3 shadow-sm border">
                <h5 class="fw-bold mb-3">Motivo y examen psicométrico</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="motivo_consulta" class="form-label">Motivo de la consulta <span class="text-danger">*</span></label>
                        <textarea
                            name="motivo_consulta"
                            id="motivo_consulta"
                            rows="2"
                            class="form-control @error('motivo_consulta') is-invalid @enderror"
                            required
                            maxlength="250"
                            onkeydown="return allowOnly(event)"
                            onpaste="return handlePaste(event)"
                        >{{ old('motivo_consulta') }}</textarea>
                        @error('motivo_consulta')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="tipo_examen" class="form-label">Tipo de examen aplicado <span class="text-danger">*</span></label>
                        <select name="tipo_examen" id="tipo_examen" class="form-select @error('tipo_examen') is-invalid @enderror" required>
                            <option value="">-- Selecciona un examen --</option>
                            @php
                                $examenes = [
                                    "Test de Inteligencia (WAIS)","Test de Raven","Test de Bender",
                                    "Test de Machover (Figura Humana)","Test de la Casa-Árbol-Persona (HTP)",
                                    "Test de Luscher (Colores)","Test de 16 Factores de Personalidad (16PF)",
                                    "Inventario Multifásico de Personalidad de Minnesota (MMPI)","Inventario de Ansiedad de Beck (BAI)",
                                    "Inventario de Depresión de Beck (BDI)","Escala de Autoestima de Rosenberg",
                                    "Test de Apercepción Temática (TAT)","Test de la Figura Compleja de Rey",
                                    "Test de Aptitudes Diferenciales (DAT)","Test de Dominós D48","Test Cleaver","Test DISC"
                                ];
                            @endphp
                            @foreach($examenes as $examen)
                                <option value="{{ $examen }}" {{ old('tipo_examen') == $examen ? 'selected' : '' }}>{{ $examen }}</option>
                            @endforeach
                        </select>
                        @error('tipo_examen')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Sección Resultado y Observaciones --}}
            <div class="card mb-3 p-3 shadow-sm border">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="resultado" class="form-label">Resultado <span class="text-danger">*</span></label>
                        <textarea
                            name="resultado"
                            id="resultado"
                            rows="3"
                            class="form-control @error('resultado') is-invalid @enderror"
                            required
                            maxlength="250"
                            onkeydown="return allowOnly(event)"
                            onpaste="return handlePaste(event)"
                        >{{ old('resultado') }}</textarea>
                        @error('resultado')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="observaciones" class="form-label">Observaciones(opcional)</label>
                        <textarea
                            name="observaciones"
                            id="observaciones"
                            rows="3"
                            class="form-control"
                            maxlength="250"
                            onkeydown="return allowOnly(event)"
                            onpaste="return handlePaste(event)"
                        >{{ old('observaciones') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Archivo --}}
            <!-- Archivo Resultado -->
            <div class="col-md-4">
                <label for="archivo_resultado" class="form-label">Archivo resultado:</label>

                <div class="mb-2">
                    <img id="archivoPreviewImg"
                         src="{{ session('archivo_temporal') && !Str::endsWith(session('archivo_temporal'), '.pdf') ? asset('storage/' . session('archivo_temporal')) : '' }}"
                         alt="Archivo seleccionado"
                         class="img-thumbnail"
                         style="max-width: 250px; max-height:150px; {{ session('archivo_temporal') && !Str::endsWith(session('archivo_temporal'), '.pdf') ? '' : 'display:none;' }}">
                    <iframe id="archivoPreviewPDF"
                            src="{{ session('archivo_temporal') && Str::endsWith(session('archivo_temporal'), '.pdf') ? asset('storage/' . session('archivo_temporal')) : '' }}"
                            style="width:100%; height:100px; {{ session('archivo_temporal') && Str::endsWith(session('archivo_temporal'), '.pdf') ? '' : 'display:none;' }}">
                    </iframe>
                </div>

                <input type="file"
                       name="archivo_resultado"
                       id="archivo_resultado"
                       accept=".jpg,.jpeg,.png,.webp,.pdf"
                       class="form-control @error('archivo_resultado') is-invalid @enderror">

                @error('archivo_resultado')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>





            {{-- Botones --}}
            {{-- Botones --}}
            <div class="d-flex justify-content-center gap-3 mt-4">
                <button type="submit" class="btn btn-primary px-4 shadow-sm d-inline-flex align-items-center gap-2">
                    <i class="bi bi-plus-circle"></i> Registrar
                </button>

                <button type="button" id="btnLimpiar" class="btn btn-warning px-4 shadow-sm d-inline-flex align-items-center gap-2">
                    <i class="bi bi-trash"></i> Limpiar
                </button>

                <a href="{{ route('sesiones.index') }}"
                   onclick="fetch('{{ route('sesiones.limpiarArchivo') }}', {
           method: 'POST',
           headers: { 'X-CSRF-TOKEN':'{{ csrf_token() }}' }
       });"
                   class="btn btn-success px-4 shadow-sm d-inline-flex align-items-center gap-2">
                    <i class="bi bi-arrow-left"></i> Regresar
                </a>
            </div>

        </form>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pacienteSelect = document.getElementById('paciente_id');
            const edadInput = document.getElementById('edad');
            const generoInput = document.getElementById('genero');
            const telefonoInput = document.getElementById('telefono');

            pacienteSelect.addEventListener('change', function() {
                const selected = pacienteSelect.selectedOptions[0];
                if(selected.value){
                    const nacimiento = new Date(selected.dataset.nacimiento);
                    const hoy = new Date();
                    let edad = hoy.getFullYear() - nacimiento.getFullYear();
                    if(hoy.getMonth() < nacimiento.getMonth() || (hoy.getMonth() === nacimiento.getMonth() && hoy.getDate() < nacimiento.getDate())){
                        edad--;
                    }
                    edadInput.value = edad;
                    generoInput.value = selected.dataset.genero;
                    telefonoInput.value = selected.dataset.telefono;
                } else {
                    edadInput.value = '';
                    generoInput.value = '';
                    telefonoInput.value = '';
                }
            });

            // Mantener valores antiguos si hay error
            const oldPaciente = "{{ old('paciente_id') }}";
            if(oldPaciente){
                pacienteSelect.value = oldPaciente;
                pacienteSelect.dispatchEvent(new Event('change'));
            }



            // Botón Limpiar
            document.getElementById('btnLimpiar').addEventListener('click', () => {
                const form = document.getElementById('formSesion');
                form.reset();
                previewContainer.style.display = 'none';
                previewImage.src = '#';
                form.querySelectorAll('input, select, textarea').forEach(el => {
                    el.classList.remove('is-invalid', 'is-valid');
                });
            });
        });
    </script>
    <script>
        function checkYear(input) {
            const year = new Date(input.value).getFullYear();
            if (year !== 2025) {
                alert('Solo se permiten fechas dentro del año 2025.');
                input.value = '';
            }
        }
    </script>

    <style>
        /* Bloquea visualmente el año y lo pone gris */
        input[type="date"]::-webkit-datetime-edit-year-field {
            color: #888;
            pointer-events: none;
        }
        input[type="date"]::-moz-date-year-field {
            color: #888;
            pointer-events: none;
        }
    </style>

    <script>
        const allowedChars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 .,;%";

        function allowOnly(e) {
            const key = e.key;

            // Permitir teclas especiales como Backspace, Enter, Tab, flechas
            const specialKeys = ["Backspace", "Delete", "ArrowLeft", "ArrowRight", "ArrowUp", "ArrowDown", "Tab", "Enter"];
            if (specialKeys.includes(key)) return true;

            if (!allowedChars.includes(key)) {
                e.preventDefault(); // Bloquea la tecla
                return false;
            }
            return true;
        }

        function handlePaste(e) {
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            for (let char of paste) {
                if (!allowedChars.includes(char)) {
                    e.preventDefault(); // Bloquea pegar si contiene caracteres no permitidos
                    return false;
                }
            }
            return true;
        }
    </script>
    {{-- JS para vista previa --}}
    <script>
        const form = document.getElementById('formSesion'); // Asegúrate de poner id="formSesion" en el form
        const archivoInput = document.getElementById('archivo_resultado');
        const archivoPreviewImg = document.getElementById('archivoPreviewImg');
        const archivoPreviewPDF = document.getElementById('archivoPreviewPDF');

        // Botón Limpiar
        document.getElementById('btnLimpiar').addEventListener('click', function(){
            form.reset();

            // Limpiar validaciones
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('.is-valid').forEach(el => el.classList.remove('is-valid'));

            // Limpiar preview
            archivoPreviewImg.src = '';
            archivoPreviewImg.style.display = 'none';
            archivoPreviewPDF.src = '';
            archivoPreviewPDF.style.display = 'none';

            // Limpiar archivo temporal del backend
            fetch("{{ route('sesiones.limpiarArchivo') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN':'{{ csrf_token() }}' }
            });
        });

        // Previsualización archivo
        archivoInput.addEventListener('change', function(e){
            const file = e.target.files[0];
            if(file){
                const reader = new FileReader();
                reader.onload = function(ev){
                    if(file.type.startsWith('image/')){
                        archivoPreviewImg.src = ev.target.result;
                        archivoPreviewImg.style.display = 'block';
                        archivoPreviewPDF.style.display = 'none';
                    } else if(file.type === 'application/pdf'){
                        archivoPreviewPDF.src = ev.target.result;
                        archivoPreviewPDF.style.display = 'block';
                        archivoPreviewImg.style.display = 'none';
                    }
                }
                reader.readAsDataURL(file);
            } else {
                archivoPreviewImg.src = '';
                archivoPreviewImg.style.display = 'none';
                archivoPreviewPDF.src = '';
                archivoPreviewPDF.style.display = 'none';
            }
        });
    </script>




    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const fechaInput = document.getElementById("fecha");

            // Fecha actual
            const hoy = new Date();

            // Fecha una semana antes (7 días)
            const semanaAntes = new Date();
            semanaAntes.setDate(hoy.getDate() - 7);

            // Convertir a formato YYYY-MM-DD
            const formatoFecha = (fecha) => fecha.toISOString().split('T')[0];

            // Aplicar límites
            fechaInput.min = formatoFecha(semanaAntes);
            fechaInput.max = formatoFecha(hoy);

            // Si la fecha ingresada no está dentro del rango, ajustarla a hoy
            fechaInput.addEventListener("change", function () {
                if (this.value < this.min) this.value = this.min;
                if (this.value > this.max) this.value = this.max;
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const fechaInput = document.getElementById("fecha");
            const horaInicio = document.getElementById("hora_inicio");
            const horaFin = document.getElementById("hora_fin");


            // Función para formatear fecha YYYY-MM-DD
            const formatoFecha = fecha => fecha.toISOString().split('T')[0];

            const hoy = new Date();
            const fechaActual = formatoFecha(hoy);

            // Limitar fechas
            const semanaAntes = new Date();
            semanaAntes.setDate(hoy.getDate() - 7);
            fechaInput.min = formatoFecha(semanaAntes);
            fechaInput.max = fechaActual;

            // Si la fecha escrita manualmente es futura, forzar hoy
            if (fechaInput.value > fechaActual) fechaInput.value = fechaActual;

            // Función que ajusta límites de hora según la fecha
            function actualizarLimitesHora() {
                const ahora = new Date();
                const horaAhora = ahora.toTimeString().slice(0,5); // HH:MM

                if (fechaInput.value === fechaActual) {
                    // Bloquear horas futuras
                    horaInicio.max = horaAhora;
                    horaFin.max = horaAhora;

                    // Limpiar valores inválidos
                    if (horaInicio.value > horaAhora) horaInicio.value = "";
                    if (horaFin.value > horaAhora) horaFin.value = "";
                } else {
                    // Quitar restricciones si fecha es pasada
                    horaInicio.removeAttribute("max");
                    horaFin.removeAttribute("max");
                }
            }

            // Función para validar rango hora inicio < hora fin
            function validarRango() {
                if (horaInicio.value && horaFin.value && horaFin.value < horaInicio.value) {

                    horaFin.value = "";
                }
            }

            // Eventos
            fechaInput.addEventListener("change", actualizarLimitesHora);
            horaInicio.addEventListener("change", function () {
                actualizarLimitesHora();
                validarRango();
            });
            horaFin.addEventListener("change", function () {
                actualizarLimitesHora();
                validarRango();
            });

            // Ejecutar al cargar la página
            actualizarLimitesHora();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formSesion');
            const btnLimpiar = document.getElementById('btnLimpiar');

            btnLimpiar.addEventListener('click', function() {
                // 1️⃣ Resetea todos los campos del formulario
                form.querySelectorAll('input, select, textarea').forEach(el => {
                    if(el.type === 'select-one') {
                        el.selectedIndex = 0; // devuelve "-- Selecciona --"
                    } else {
                        el.value = '';
                    }
                    // Quita clases de validación
                    el.classList.remove('is-invalid', 'is-valid');
                });

                // 2️⃣ Limpia los campos dependientes (edad, género, teléfono)
                const edadInput = document.getElementById('edad');
                const generoInput = document.getElementById('genero');
                const telefonoInput = document.getElementById('telefono');
                if(edadInput) edadInput.value = '';
                if(generoInput) generoInput.value = '';
                if(telefonoInput) telefonoInput.value = '';

                // 3️⃣ Oculta los mensajes de error de Laravel
                form.querySelectorAll('.invalid-feedback, .valid-feedback').forEach(el => {
                    el.innerText = '';
                    el.style.display = 'none';
                });

                // 4️⃣ Oculta vista previa de archivo
                const previewContainer = document.getElementById('previewContainer');
                const previewImage = document.getElementById('previewImage');
                if(previewContainer) previewContainer.style.display = 'none';
                if(previewImage) previewImage.src = '#';
            });
        });
    </script>


@endsection
