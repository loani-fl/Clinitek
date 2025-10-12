@extends('layouts.app')

@section('title', 'Editar Inventario Médico')

@section('content')
<style>
.custom-card {
    max-width: 1200px;
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

input.form-control,
textarea.form-control,
select.form-select {
    font-size: 1.05rem;
    border: 2px solid #ced4da;
    transition: all 0.3s ease;
}

input.form-control:focus,
textarea.form-control:focus,
select.form-select:focus {
    border-color: #007BFF;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.15);
}

input.is-invalid, textarea.is-invalid, select.is-invalid {
    border-color: red;
}

.invalid-feedback {
    display: block;
}

.alert-custom {
    padding: 0.75rem;
    border-radius: 0.25rem;
    margin-top: 0.5rem;
    font-size: 0.95rem;
}
</style>

<div class="custom-card">
    <div class="mb-4 text-center" style="border-bottom: 3px solid #007BFF;">
        <h2 class="fw-bold text-black mb-0">Editar Inventario Médico</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-custom" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('inventario.update', $inventario->id) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <!-- Fila 1: Nombre, Categoría, Cantidad, Unidad, Precio -->
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                <input type="text" name="nombre" id="nombre" class="form-control form-control-sm @error('nombre') is-invalid @enderror" maxlength="100" value="{{ old('nombre', $inventario->nombre) }}" required>
                @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-2">
                <label for="categoria" class="form-label">Categoría <span class="text-danger">*</span></label>
                <select name="categoria" id="categoria" class="form-select form-select-sm @error('categoria') is-invalid @enderror" required>
                    <option value="">Seleccione</option>
                    <option value="Medicamentos" {{ old('categoria', $inventario->categoria) == 'Medicamentos' ? 'selected' : '' }}>Medicamentos</option>
                    <option value="Insumos médicos" {{ old('categoria', $inventario->categoria) == 'Insumos médicos' ? 'selected' : '' }}>Insumos médicos</option>
                    <option value="Equipos" {{ old('categoria', $inventario->categoria) == 'Equipos' ? 'selected' : '' }}>Equipos</option>
                    <option value="Material de limpieza" {{ old('categoria', $inventario->categoria) == 'Material de limpieza' ? 'selected' : '' }}>Material de limpieza</option>
                </select>
                @error('categoria') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-2">
                <label for="cantidad" class="form-label">Cantidad <span class="text-danger">*</span></label>
                <input type="number" name="cantidad" id="cantidad" class="form-control form-control-sm @error('cantidad') is-invalid @enderror" value="{{ old('cantidad', $inventario->cantidad) }}" min="0">
                @error('cantidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <small class="text-muted">Máx 5 dígitos.</small>
            </div>

            <div class="col-md-2">
                <label for="unidad" class="form-label">Unidad de medida</label>
                <input type="text" name="unidad" id="unidad" class="form-control form-control-sm @error('unidad') is-invalid @enderror" value="{{ old('unidad', $inventario->unidad) }}" maxlength="50" placeholder="cajas, frascos, etc.">
                @error('unidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-2">
                <label for="precio_unitario" class="form-label">Precio (L.) <span class="text-danger">*</span></label>
                <input type="number" name="precio_unitario" id="precio_unitario" class="form-control form-control-sm @error('precio_unitario') is-invalid @enderror" step="0.01" min="0" value="{{ old('precio_unitario', $inventario->precio_unitario) }}">
                @error('precio_unitario') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <small class="text-muted">Máx 8 dígitos</small>
            </div>
        </div>

        <!-- Fila 2: Fechas -->
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="fecha_ingreso" class="form-label">Fecha ingreso <span class="text-danger">*</span></label>
                <input type="date" name="fecha_ingreso" id="fecha_ingreso" class="form-control form-control-lg" value="{{ old('fecha_ingreso', $inventario->fecha_ingreso) }}" required>
            </div>
            <div class="col-md-3">
                <label for="fecha_vencimiento" class="form-label">Fecha vencimiento</label>
                <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" class="form-control form-control-lg @error('fecha_vencimiento') is-invalid @enderror" value="{{ old('fecha_vencimiento', $inventario->fecha_vencimiento) }}">
                @error('fecha_vencimiento') 
                    <div class="invalid-feedback">{{ $message }}</div> 
                @enderror
            </div>
            <div class="col-md-2"></div>
        </div>

        <!-- Fila 3: Descripción y Observaciones -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="descripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
                <textarea name="descripcion" id="descripcion" class="form-control form-control-lg @error('descripcion') is-invalid @enderror" rows="2" maxlength="200">{{ old('descripcion', $inventario->descripcion) }}</textarea>
                @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label for="observaciones" class="form-label">Observaciones <span class="text-danger">*</span></label>
                <textarea name="observaciones" id="observaciones" class="form-control form-control-lg @error('observaciones') is-invalid @enderror" rows="2" maxlength="200">{{ old('observaciones', $inventario->observaciones) }}</textarea>
                @error('observaciones') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <!-- Botones con símbolos y regresar -->
        <div class="d-flex justify-content-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle me-1"></i> Actualizar
            </button>
            <button type="button" id="btnLimpiar" class="btn btn-warning">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Restablecer
            </button>
            <a href="{{ route('inventario.index') }}" class="btn btn-success">
                <i class="bi bi-arrow-left-circle me-1"></i> Regresar
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Guardar valores originales al cargar la página
    const valoresOriginales = {};
    const form = document.querySelector('form');
    
    form.querySelectorAll('input, textarea, select').forEach(input => {
        valoresOriginales[input.id] = input.value;
    });

    // Cantidad: solo números y max 5
    const cantidad = document.getElementById('cantidad');
    cantidad.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g,'').slice(0,5);
    });

    // Precio unitario: solo números y punto decimal
    const precio = document.getElementById('precio_unitario');
    precio.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9.]/g,'');
        const parts = this.value.split('.');
        if(parts[0].length>8) parts[0]=parts[0].slice(0,8);
        this.value = parts.join('.');
        if((this.value.match(/\./g)||[]).length>1) this.value=this.value.slice(0,-1);
    });

    // Bloquear caracteres raros en campos de texto
    ['nombre','unidad','descripcion','observaciones'].forEach(id=>{
        const input=document.getElementById(id);
        if(input){
            input.addEventListener('input', function(){
                this.value=this.value.replace(/[^A-Za-zÀ-ÿ0-9\s.,()-]/g,'').slice(0,this.getAttribute('maxlength'));
            });
        }
    });

    // Botón Limpiar (Restablecer a valores originales)
    const btnLimpiar = document.getElementById('btnLimpiar');

    btnLimpiar.addEventListener('click', () => {
        form.querySelectorAll('input, textarea, select').forEach(input => {
            input.value = valoresOriginales[input.id] || '';
            input.classList.remove('is-invalid');
        });

        // Quitar mensajes de validación
        form.querySelectorAll('.invalid-feedback').forEach(msg => {
            if (!msg.textContent.includes('{{')) {
                msg.style.display = 'none';
            }
        });
    });
});
</script>
@endsection