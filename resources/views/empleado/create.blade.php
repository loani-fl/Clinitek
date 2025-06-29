@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #e8f4fc;
        margin: 0;
        padding: 0;
    }

    /* Barra de navegación fija */
    .header {
        background-color: #007BFF;
        position: fixed; /* fijo para que siempre esté visible */
        top: 0;
        left: 0;
        right: 0;
        z-index: 1100;
        padding: 0.5rem 1rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
    }

    /* Para que el contenido no quede oculto ajo la barra */
    .content-wrapper {
        margin-top: 60px; /* más espacio para que la card no quede detrás de la navbar */
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

    .custom-card {
        max-width: 900px; /* más delgada */
        background-color: #fff;
        margin: 40px auto 40px;
        border-radius: 1.5rem;
        padding: 1rem;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #003366;
    }

    input, select, textarea {
        font-size: 0.85rem !important;
    }

    .card-header {
        background-color: transparent;
        border-bottom: 3px solid #007BFF;
        padding-top: 0.25rem; /* menos padding arriba */
        padding-bottom: 0.25rem; /* menos padding abajo */
    }

    .card-header h3 {
        font-size: 1.5rem; /* tamaño más pequeño para el título */
        font-weight: bold;
        color: #000;
        margin: 0;
    }

    .btn {
        font-size: 0.9rem;
    }

    .valid-feedback {
        display: block;
        font-size: 0.85rem;
        color: #198754;
    }

    .form-control.is-valid {
        border-color: #198754;
        padding-right: 2.5rem;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' stroke='%23198754' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' class='bi bi-check-lg' viewBox='0 0 16 16'%3e%3cpath d='M13 4.5 6 11.5 3 8.5'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1rem 1rem;
    }

    label.is-invalid {
        color: #dc3545;
        font-weight: 600;
    }
</style>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Barra de navegación fija --}}
<div class="header d-flex justify-content-between align-items-center px-3 py-2">
    {{-- Logo y título --}}
    <div class="d-flex align-items-center">
        <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek" 
        style="height: 40px; width: auto;">
        <div class="fw-bold text-white" style="font-size: 1.5rem; margin-left: 8px;">Clinitek</div>
    </div>

    {{-- Botones --}}
    <div class="d-flex gap-3 flex-wrap">
        <a href="{{ route('puestos.create') }}" class="nav-link text-white">Crear Puesto</a>
        <a href="{{ route('medicos.create') }}" class="nav-link text-white">Registro médicos</a>
        <a href="{{ route('pacientes.index') }}" class="nav-link text-white">Registro Pacientes</a>
    </div>
</div>

{{-- Contenedor para aplicar el margin-top --}}
<div class="content-wrapper">
    <div class="card custom-card shadow-sm">
        <div class="card-header text-center">
            <h3>Registrar nuevo empleado</h3>
        </div>

   <form action="{{ route('empleado.store') }}" method="POST" novalidate class="card-body" id="formEmpleado" enctype="multipart/form-data">
        @csrf
        <div class="row g-3 mt-2">

  <div class="row mb-3">
    <div class="col-md-3">
        <label for="nombres" class="form-label">Nombres <span class="text-danger">*</span></label>
        <input type="text" name="nombres" id="nombres" class="form-control" 
            value="{{ old('nombres') }}" required maxlength="50" 
            pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo se permiten letras y espacios">
        @error('nombres') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3">
        <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
        <input type="text" name="apellidos" id="apellidos" class="form-control" 
            value="{{ old('apellidos') }}" required maxlength="50" 
            pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo se permiten letras y espacios">
        @error('apellidos') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3">
        <label for="identidad" class="form-label">Identidad <span class="text-danger">*</span></label>
        <input type="text" name="identidad" id="identidad" class="form-control" 
            value="{{ old('identidad') }}" maxlength="13" required pattern="\d{13}" 
            title="Debe tener exactamente 13 números, sin letras ni símbolos">
        @error('identidad') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3">
        <label for="telefono" class="form-label">Teléfono <span class="text-danger">*</span></label>
        <input type="text" name="telefono" id="telefono" class="form-control" 
            value="{{ old('telefono') }}" maxlength="8" required pattern="\d{8}" 
            title="Debe tener exactamente 8 números, sin letras ni símbolos">
        @error('telefono') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-3">
        <label for="correo" class="form-label">Correo <span class="text-danger">*</span></label>
        <input type="email" name="correo" id="correo" class="form-control" 
            value="{{ old('correo') }}" maxlength="30" required>
        @error('correo') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        @php $fechaMax = now()->subYears(18)->format('Y-m-d'); @endphp
        <label for="fecha_nacimiento" class="form-label">Fecha Nacimiento <span class="text-danger">*</span></label>
        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" 
            value="{{ old('fecha_nacimiento') }}" max="{{ $fechaMax }}" required>
        @error('fecha_nacimiento') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        @php
            $hoy = now();
            $minIngreso = $hoy->copy()->subMonth()->format('Y-m-d');
            $maxIngreso = $hoy->copy()->addMonth()->format('Y-m-d');
        @endphp
        <label for="fecha_ingreso" class="form-label">Fecha Ingreso <span class="text-danger">*</span></label>
        <input type="date" name="fecha_ingreso" id="fecha_ingreso" class="form-control" 
            value="{{ old('fecha_ingreso') }}" min="{{ $minIngreso }}" max="{{ $maxIngreso }}" required>
        @error('fecha_ingreso') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label for="estado_civil" class="form-label">Estado Civil</label>
        <select name="estado_civil" id="estado_civil" class="form-select">
            <option value="">-- Selecciona --</option>
            <option value="Soltero" {{ old('estado_civil') == 'Soltero' ? 'selected' : '' }}>Soltero</option>
            <option value="Casado" {{ old('estado_civil') == 'Casado' ? 'selected' : '' }}>Casado</option>
            <option value="Divorciado" {{ old('estado_civil') == 'Divorciado' ? 'selected' : '' }}>Divorciado</option>
            <option value="Viudo" {{ old('estado_civil') == 'Viudo' ? 'selected' : '' }}>Viudo</option>
        </select>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-3">
        <label for="genero" class="form-label">Género <span class="text-danger">*</span></label>
        <select name="genero" id="genero" class="form-select" required>
            <option value="">-- Selecciona --</option>
            <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
            <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
            <option value="Otro" {{ old('genero') == 'Otro' ? 'selected' : '' }}>Otro</option>
        </select>
        @error('genero') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3">
        <label for="area" class="form-label">Área <span class="text-danger">*</span></label>
        <select name="area" id="area" class="form-select" required onchange="autoFillSalario()">
            <option value="">-- Selecciona --</option>
            <option value="Administración" data-sueldo="15000" {{ old('area') == 'Administración' ? 'selected' : '' }}>Administración</option>
            <option value="Recepción" data-sueldo="12000" {{ old('area') == 'Recepción' ? 'selected' : '' }}>Recepción</option>
            <option value="Laboratorio" data-sueldo="18000" {{ old('area') == 'Laboratorio' ? 'selected' : '' }}>Laboratorio</option>
            <option value="Farmacia" data-sueldo="16000" {{ old('area') == 'Farmacia' ? 'selected' : '' }}>Farmacia</option>
            <option value="Enfermería" data-sueldo="17000" {{ old('area') == 'Enfermería' ? 'selected' : '' }}>Enfermería</option>
            <option value="Mantenimiento" data-sueldo="11000" {{ old('area') == 'Mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
        </select>
        @error('area') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label for="puesto_id" class="form-label">Puesto <span class="text-danger">*</span></label>
        <select name="puesto_id" id="puesto_id" class="form-select" required onchange="autoFillSalario()">
            <option value="">-- Selecciona --</option>
            @foreach($puestos as $puesto)
                <option value="{{ $puesto->id }}" data-sueldo="{{ $puesto->sueldo }}" {{ old('puesto_id') == $puesto->id ? 'selected' : '' }}>
                    {{ $puesto->nombre }}
                </option>
            @endforeach
        </select>
        @error('puesto_id') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label for="turno_asignado" class="form-label">Turno <span class="text-danger">*</span></label>
        <select name="turno_asignado" id="turno_asignado" class="form-select" required>
            <option value="">-- Selecciona --</option>
            <option value="Mañana" {{ old('turno_asignado') == 'Mañana' ? 'selected' : '' }}>Mañana</option>
            <option value="Tarde" {{ old('turno_asignado') == 'Tarde' ? 'selected' : '' }}>Tarde</option>
            <option value="Noche" {{ old('turno_asignado') == 'Noche' ? 'selected' : '' }}>Noche</option>
        </select>
        @error('turno_asignado') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-3">
        <label for="salario" class="form-label">Salario (Lps.)</label>
        <input type="text" name="salario" id="salario" class="form-control" readonly value="{{ old('salario') ?? '' }}">
        @error('salario') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-5">
        <label for="foto" class="form-label fw-semibold">Foto:</label>
        <input 
            type="file" 
            name="foto" 
            id="foto" 
            class="form-control @error('foto') is-invalid @enderror"
            accept=".jpg,.jpeg,.png,.gif">
        <small class="form-text text-muted">
            Opcional. Formato: JPG, JPEG, PNG o GIF. Máximo 2MB.
        </small>
        @error('foto')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

{{-- Dirección y Observaciones en fila aparte --}}
<div class="row mb-3">
    <div class="col-md-6">
        <label for="direccion" class="form-label">Dirección <span class="text-danger">*</span></label>
        <textarea name="direccion" id="direccion" class="form-control" rows="3" required maxlength="350">{{ old('direccion') }}</textarea>
        @error('direccion') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="observaciones" class="form-label">Observaciones <span class="text-danger">*</span></label>
        <textarea name="observaciones" id="observaciones" class="form-control" rows="3" required maxlength="200">{{ old('observaciones') }}</textarea>
        @error('observaciones') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>
</div>


            {{-- Botones centrados --}}
            <div class="d-flex justify-content-center gap-3 mt-4 w-100">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Registrar
                </button>

                <button type="button" class="btn btn-warning" id="btnLimpiar">
                    <i class="bi bi-trash"></i> Limpiar
                </button>

                <a href="{{ route('empleado.index') }}" class="btn btn-success">
                    <i class="bi bi-arrow-left"></i> Regresar
                </a>
            </div>

        </div>
    </form>
</div>
<script>

    window.addEventListener('load', () => {
  const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
  if (!hasErrors) {
    // Si no venimos con errores de validación, limpiamos todo
    Object.keys(localStorage).forEach(key => {
      if (key.startsWith('empleado_')) {
        localStorage.removeItem(key);
      }
    });
  }
  // Después de esto, ejecutamos tu carga normal de datos / actualización de salario
  actualizarSalario();
});

  // --- Función para actualizar salario ---
  function actualizarSalario() {
    const puestoSelect = document.getElementById('puesto_id');
    const salarioInput = document.getElementById('salario');
    if (!puestoSelect || !salarioInput) return;
    const seleccionado = puestoSelect.options[puestoSelect.selectedIndex];
    const sueldo = seleccionado ? seleccionado.getAttribute('data-sueldo') : '';
    salarioInput.value = sueldo ? parseFloat(sueldo).toFixed(2) : '';
  }

  // --- Evento para botón limpiar ---
  document.getElementById('btnLimpiar').addEventListener('click', function () {
    const form = this.closest('form');
    form.reset();

    // Limpiar localStorage
    document.querySelectorAll('input, select, textarea').forEach(el => {
      localStorage.removeItem('empleado_' + el.name);
    });

    // Limpiar salario manualmente
    const salarioInput = document.getElementById('salario');
    if (salarioInput) salarioInput.value = '';

    // Quitar clases de validación
    form.querySelectorAll('.is-invalid, .is-valid').forEach(el => {
      el.classList.remove('is-invalid', 'is-valid');
    });

    // Eliminar mensajes de error
    form.querySelectorAll('.text-danger').forEach(el => {
      el.innerHTML = '';
    });

    // Resetear selects si reset no funciona bien
    form.querySelectorAll('select').forEach(select => {
      select.selectedIndex = 0;
    });

    // Actualizar salario (queda vacío)
    actualizarSalario();
  });

  // --- Actualizar salario al cambiar el puesto ---
  const puestoSelect = document.getElementById('puesto_id');
  if (puestoSelect) {
    puestoSelect.addEventListener('change', actualizarSalario);
  }

  // --- Validaciones en tiempo real ---

  // Teléfono
  const telefonoInput = document.getElementById('telefono');
  const telefonoError = document.getElementById('telefono-error');
  if (telefonoInput && telefonoError) {
    telefonoInput.addEventListener('input', () => {
      const val = telefonoInput.value.trim();
      const regex = /^[238]\d{7}$/;
      if (regex.test(val)) {
        telefonoError.style.display = 'none';
        telefonoInput.setCustomValidity('');
      } else {
        telefonoError.style.display = 'block';
        telefonoInput.setCustomValidity('Teléfono inválido');
      }
    });
  }

  // Correo
  const correoInput = document.getElementById('correo');
  const correoError = document.getElementById('correo-error');
  if (correoInput && correoError) {
    correoInput.addEventListener('input', () => {
      const val = correoInput.value.trim();
      const puntos = (val.match(/\./g) || []).length;
      const regex = /^[a-zA-Z0-9@.]+$/;
      if (val.length <= 30 && regex.test(val) && puntos <= 3) {
        correoError.style.display = 'none';
        correoInput.setCustomValidity('');
      } else {
        correoError.style.display = 'block';
        correoInput.setCustomValidity('Correo inválido');
      }
    });
  }

  // --- Guardar datos en localStorage al escribir ---
  document.querySelectorAll('input, select, textarea').forEach(el => {
    el.addEventListener('input', () => {
      localStorage.setItem('empleado_' + el.name, el.value);
    });
  });

  // --- Cargar datos y limpiar localStorage al cargar la página ---
  window.addEventListener('load', () => {
    @if(session('clearLocalStorage'))
      // Limpiar localStorage sólo si venimos de registro exitoso
      Object.keys(localStorage).forEach(key => {
        if (key.startsWith('empleado_')) {
          localStorage.removeItem(key);
        }
      });
    @else
      // Si no, cargar localStorage en campos vacíos
      document.querySelectorAll('input, select, textarea').forEach(el => {
        if (!el.value) {
          const saved = localStorage.getItem('empleado_' + el.name);
          if (saved !== null) {
            el.value = saved;
          }
        }
      });
    @endif

    // Actualizar salario según campo cargado
    actualizarSalario();
  });

  // --- Validación dinámica con Bootstrap ---
  document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    if (!form) return;
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    inputs.forEach(input => {
      input.addEventListener('input', () => validateField(input));
      input.addEventListener('change', () => validateField(input));
    });

    function validateField(field) {
      if (field.checkValidity()) {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
        const errorDiv = field.nextElementSibling;
        if (errorDiv && errorDiv.classList.contains('error-text')) {
          errorDiv.style.display = 'none';
          errorDiv.textContent = '';
        }
      } else {
        field.classList.remove('is-valid');
        if (!field.classList.contains('is-invalid')) {
          field.classList.add('is-invalid');
        }
      }
    }
  });

  // --- Bloqueos de teclas y filtrados ---

  // Nombres y Apellidos: solo letras y espacios
  (function(){
    function bloquearNoLetras(e) {
      const code = e.keyCode;
      const tecla = e.key;
      const teclasCtrl = [8,9,37,38,39,40,46];
      const regexLetra = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]$/;
      if (!teclasCtrl.includes(code) && !regexLetra.test(tecla)) e.preventDefault();
    }
    function limpiarNoLetrasOnPaste(e) {
      setTimeout(() => {
        this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '');
      }, 0);
    }
    ['nombres','apellidos'].forEach(id => {
      const el = document.getElementById(id);
      if (el) {
        el.addEventListener('keydown', bloquearNoLetras);
        el.addEventListener('paste', limpiarNoLetrasOnPaste);
      }
    });
  })();

  // Identidad y Teléfono: solo números
  (function(){
    function bloquearSoloNumeros(e) {
      const code = e.keyCode;
      const tecla = e.key;
      const teclasCtrl = [8,9,37,38,39,40,46];
      const regexNumero = /^[0-9]$/;
      if (!teclasCtrl.includes(code) && !regexNumero.test(tecla)) e.preventDefault();
    }
    function limpiarSoloNumerosOnPaste(e) {
      setTimeout(() => {
        this.value = this.value.replace(/[^0-9]/g, '');
      }, 0);
    }
    ['identidad','telefono'].forEach(id => {
      const el = document.getElementById(id);
      if (el) {
        el.addEventListener('keydown', bloquearSoloNumeros);
        el.addEventListener('paste', limpiarSoloNumerosOnPaste);
      }
    });
  })();

  // Dirección y Observaciones: caracteres válidos específicos
  (function(){
    const regexValido = /^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s,.;:\-()#]$/;
    function bloquearCaracteresInvalidos(e) {
      const code = e.keyCode;
      const tecla = e.key;
      const teclasCtrl = [8,9,37,38,39,40,46];
      if (!teclasCtrl.includes(code) && !regexValido.test(tecla)) e.preventDefault();
    }
    function limpiarOnPaste(e) {
      setTimeout(() => {
        this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s,.;:\-()#]/g, '');
      }, 0);
    }
    ['direccion','observaciones'].forEach(id => {
      const el = document.getElementById(id);
      if (el) {
        el.addEventListener('keydown', bloquearCaracteresInvalidos);
        el.addEventListener('paste', limpiarOnPaste);
      }
    });
  })();
</script>



@endsection