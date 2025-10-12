@extends('layouts.app')

@section('title', 'Registrar Inventario Médico')

@section('content')
<style>
.custom-card {
    max-width: 900px;
    background-color: rgba(255, 255, 255, 0.95);
    border: 1px solid #91cfff;
    border-radius: 0.5rem;
    margin: 2rem auto;
    padding: 1.5rem;
    box-shadow: 0 0 15px rgba(0,123,255,0.25);
    position: relative;
    overflow: hidden;
}

.custom-card::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 700px;
    height: 700px;
    background-image: url('/images/logo2.jpg');
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    opacity: 0.12;
    transform: translate(-50%, -50%);
    pointer-events: none;
}

input.form-control,
textarea.form-control,
select.form-select {
    font-size: 0.95rem;
    border: 2px solid #ced4da;
    transition: all 0.3s ease;
}

input.form-control:focus,
textarea.form-control:focus,
select.form-select:focus {
    border-color: #007BFF;
    box-shadow: 0 0 0 0.15rem rgba(0,123,255,0.15);
}

input.is-invalid, textarea.is-invalid, select.is-invalid {
    border-color: red;
}

.invalid-feedback {
    display: block;
}
</style>

<div class="custom-card">
    <div class="mb-4 text-center border-bottom border-3 border-primary">
        <h2 class="fw-bold text-black mb-0">Inventario </h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-custom" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('inventario.store') }}" method="POST" novalidate>
        @csrf

        <!-- Fila 1 -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="codigo" class="form-label">Código <span class="text-danger">*</span></label>
                <input type="text" name="codigo" id="codigo"
                       class="form-control form-control-sm @error('codigo') is-invalid @enderror"
                       maxlength="10" value="{{ old('codigo') }}" required>
                @error('codigo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <small class="text-muted">Ej: MED-001</small>
            </div>

            <div class="col-md-4">
                <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                <input type="text" name="nombre" id="nombre"
                       class="form-control form-control-sm @error('nombre') is-invalid @enderror"
                       maxlength="100" value="{{ old('nombre') }}" required>
                @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label for="categoria" class="form-label">Categoría <span class="text-danger">*</span></label>
                <select name="categoria" id="categoria"
                        class="form-select form-select-sm @error('categoria') is-invalid @enderror" required>
                    <option value="">Seleccione</option>
                    <option value="Medicamentos" {{ old('categoria')=='Medicamentos'?'selected':'' }}>Medicamentos</option>
                    <option value="Insumos médicos" {{ old('categoria')=='Insumos médicos'?'selected':'' }}>Insumos médicos</option>
                    <option value="Equipos" {{ old('categoria')=='Equipos'?'selected':'' }}>Equipos</option>
                    <option value="Material de limpieza" {{ old('categoria')=='Material de limpieza'?'selected':'' }}>Material de limpieza</option>
                    <option value="Papelería" {{ old('categoria')=='Papelería'?'selected':'' }}>Papelería</option>
                </select>
                @error('categoria') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <!-- Fila 2 -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="cantidad" class="form-label">Cantidad <span class="text-danger">*</span></label>
                <input type="number" name="cantidad" id="cantidad"
                       class="form-control form-control-sm @error('cantidad') is-invalid @enderror"
                       value="{{ old('cantidad') }}" min="0">
                @error('cantidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label for="unidad" class="form-label">Unidad</label>
                <input type="text" name="unidad" id="unidad"
                       class="form-control form-control-sm @error('unidad') is-invalid @enderror"
                       value="{{ old('unidad') }}" maxlength="50" placeholder="cajas, frascos, etc.">
                @error('unidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label for="precio_unitario" class="form-label">Precio (L.) <span class="text-danger">*</span></label>
                <input type="number" name="precio_unitario" id="precio_unitario"
                       class="form-control form-control-sm @error('precio_unitario') is-invalid @enderror"
                       step="0.01" min="0" value="{{ old('precio_unitario') }}">
                @error('precio_unitario') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <!-- Fila 3 -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="fecha_ingreso" class="form-label">Fecha ingreso <span class="text-danger">*</span></label>
                <input type="date" name="fecha_ingreso" id="fecha_ingreso"
                       class="form-control form-control-sm"
                       value="{{ old('fecha_ingreso', now()->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-6">
                <label for="fecha_vencimiento" class="form-label">Fecha vencimiento</label>
                <input type="date" name="fecha_vencimiento" id="fecha_vencimiento"
                       class="form-control form-control-sm @error('fecha_vencimiento') is-invalid @enderror"
                       value="{{ old('fecha_vencimiento') }}">
                @error('fecha_vencimiento') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <!-- Descripción -->
<div class="row mb-3">
    <div class="col-md-12">
        <label for="descripcion" class="form-label fw-semibold">Descripción <span class="text-danger">*</span></label>
        <textarea name="descripcion" id="descripcion"
                  class="form-control form-control-sm @error('descripcion') is-invalid @enderror"
                  rows="3" maxlength="200">{{ old('descripcion') }}</textarea>
        @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

        <!-- Botones -->
        <div class="d-flex justify-content-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle me-1"></i> Registrar
            </button>
            <button type="button" id="btnLimpiar" class="btn btn-warning">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Limpiar
            </button>
            <a href="{{ route('inventario.index') }}" class="btn btn-success">
                <i class="bi bi-arrow-left-circle me-1"></i> Regresar
            </a>
        </div>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const cantidad = document.getElementById('cantidad');
    cantidad.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5);
    });

    const precio = document.getElementById('precio_unitario');
    precio.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9.]/g, '');
        const parts = this.value.split('.');
        if (parts[0].length > 8) parts[0] = parts[0].slice(0, 8);
        this.value = parts.join('.');
        if ((this.value.match(/\./g) || []).length > 1) this.value = this.value.slice(0, -1);
    });

    // Solo letras y espacios para nombre y unidad
    const soloLetras = ['nombre', 'unidad'];
    soloLetras.forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('input', function() {
                this.value = this.value
                    .replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '') // solo letras y espacios
                    .replace(/\s{2,}/g, ' ') // evita espacios dobles
                    .trimStart(); // evita espacio inicial
            });
        }
    });

    // Los demás (como descripción y código) mantienen su validación original
    ['codigo', 'descripcion'].forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('input', function() {
                this.value = this.value.replace(/[^A-Za-z0-9À-ÿ\s.,()-]/g, '').slice(0, this.getAttribute('maxlength'));
            });
        }
    });

    // Botón limpiar (no limpia fecha de ingreso)
    const form = document.querySelector('form');
    const btnLimpiar = document.getElementById('btnLimpiar');
    btnLimpiar.addEventListener('click', () => {
        form.querySelectorAll('input, textarea, select').forEach(input => {
            if (input.id !== 'fecha_ingreso') {
                input.value = '';
                input.classList.remove('is-invalid');
            }
        });
        form.querySelectorAll('.invalid-feedback').forEach(msg => msg.remove());
    });
});
</script>

@endsection
