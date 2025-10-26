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
        <h2 class="fw-bold text-black mb-0">Inventario</h2>
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
                <label for="categoria" class="form-label">Categoría <span class="text-danger">*</span></label>
                <select name="categoria" id="categoria"
                        class="form-select form-select-sm @error('categoria') is-invalid @enderror" required>
                    <option value="">Seleccione</option>
                    <option value="Insumos médicos" {{ old('categoria')=='Insumos médicos'?'selected':'' }}>Insumos médicos</option>
                    <option value="Equipos" {{ old('categoria')=='Equipos'?'selected':'' }}>Equipos</option>
                    <option value="Material de limpieza" {{ old('categoria')=='Material de limpieza'?'selected':'' }}>Material de limpieza</option>
                    <option value="Papelería" {{ old('categoria')=='Papelería'?'selected':'' }}>Papelería</option>
                </select>
                @error('categoria') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label for="codigo" class="form-label">Código <span class="text-danger">*</span></label>
                <input type="text" name="codigo" id="codigo"
                       class="form-control form-control-sm @error('codigo') is-invalid @enderror"
                       maxlength="10" value="{{ old('codigo') }}" readonly required>
                @error('codigo') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                <input type="text" name="nombre" id="nombre"
                       class="form-control form-control-sm @error('nombre') is-invalid @enderror"
                       maxlength="100" value="{{ old('nombre') }}" required>
                @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <!-- Fila 2 -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="cantidad" class="form-label">Cantidad <span class="text-danger">*</span></label>
                <input type="number" name="cantidad" id="cantidad"
                       class="form-control form-control-sm @error('cantidad') is-invalid @enderror"
                       value="{{ old('cantidad') }}" min="1" max="99999" required>
                @error('cantidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label for="unidad" class="form-label">Unidad <span class="text-danger">*</span></label>
                <select name="unidad" id="unidad"
                        class="form-select form-select-sm @error('unidad') is-invalid @enderror" required>
                    <option value="">Seleccione</option>
                    <option value="Cajas" {{ old('unidad')=='Cajas' ? 'selected' : '' }}>Cajas</option>
                    <option value="Frascos" {{ old('unidad')=='Frascos' ? 'selected' : '' }}>Frascos</option>
                    <option value="Sobres" {{ old('unidad')=='Sobres' ? 'selected' : '' }}>Sobres</option>
                    <option value="Paquetes" {{ old('unidad')=='Paquetes' ? 'selected' : '' }}>Paquetes</option>
                    <option value="Unidades" {{ old('unidad')=='Unidades' ? 'selected' : '' }}>Unidades</option>
                    <option value="Litros" {{ old('unidad')=='Litros' ? 'selected' : '' }}>Litros</option>
                    <option value="Mililitros" {{ old('unidad')=='Mililitros' ? 'selected' : '' }}>Mililitros</option>
                </select>
                @error('unidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label for="precio_unitario" class="form-label">Precio (L.) <span class="text-danger">*</span></label>
                <input type="number" name="precio_unitario" id="precio_unitario"
                       class="form-control form-control-sm @error('precio_unitario') is-invalid @enderror"
                       step="0.01" min="0.01" max="99999.99" value="{{ old('precio_unitario') }}" required>
                @error('precio_unitario') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <!-- Fecha ingreso -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="fecha_ingreso" class="form-label">Fecha ingreso <span class="text-danger">*</span></label>
                <input type="date" name="fecha_ingreso" id="fecha_ingreso"
                       class="form-control form-control-sm"
                       value="{{ old('fecha_ingreso', now()->format('Y-m-d')) }}" required>
            </div>
        </div>

        <!-- Descripción -->
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="descripcion" class="form-label fw-semibold">Descripción <span class="text-danger">*</span></label>
                <textarea name="descripcion" id="descripcion"
                          class="form-control form-control-sm @error('descripcion') is-invalid @enderror"
                          rows="3" maxlength="200" required>{{ old('descripcion') }}</textarea>
                @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <!-- Botones -->
<div class="d-flex justify-content-center gap-3 mt-4 mb-4">
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Registrar
    </button>
    <button type="button" id="btnLimpiar" class="btn btn-warning">
        <i class="bi bi-trash"></i> Limpiar
    </button>
    <a href="{{ route('inventario.index') }}" class="btn btn-success">
        <i class="bi bi-arrow-left"></i> Regresar
    </a>
</div>


    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const categoria = document.getElementById('categoria');
    const codigo = document.getElementById('codigo');
    const form = document.querySelector('form');
    const fechaIngreso = document.getElementById('fecha_ingreso');

    // === Limitar fecha de ingreso ===
    const today = new Date();
    const maxDate = today.toISOString().split('T')[0];
    const minDate = new Date();
    minDate.setMonth(minDate.getMonth() - 2);
    const minDateStr = minDate.toISOString().split('T')[0];

    fechaIngreso.max = maxDate;
    fechaIngreso.min = minDateStr;

    // Generar código automáticamente
    categoria.addEventListener('change', function() {
        const categoriaSeleccionada = this.value;
        if (categoriaSeleccionada) {
            fetch('{{ route("inventario.generarCodigo") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ categoria: categoriaSeleccionada })
            })
            .then(res => res.json())
            .then(data => {
                codigo.value = data.codigo || '';
            })
            .catch(() => codigo.value = '');
        } else {
            codigo.value = '';
        }
    });

    // Validaciones numéricas
    const cantidad = document.getElementById('cantidad');
    cantidad.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5);
        if (this.value.startsWith('0')) this.value = this.value.replace(/^0+/, '');
        if (this.value !== '' && parseInt(this.value) < 1) this.value = '';
    });

    const precio = document.getElementById('precio_unitario');
    precio.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9.]/g, '');
        const parts = this.value.split('.');
        parts[0] = parts[0].slice(0, 5);
        if (parts[1]) parts[1] = parts[1].slice(0, 2);
        this.value = parts.join('.');
        if (parseFloat(this.value) <= 0) this.value = '';
    });

    // Solo letras para el campo nombre
    const nombre = document.getElementById('nombre');
    nombre.addEventListener('input', function() {
        this.value = this.value
            .replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '')
            .replace(/\s{2,}/g, ' ')
            .trimStart();
    });

    // Botón limpiar
    const btnLimpiar = document.getElementById('btnLimpiar');
    btnLimpiar.addEventListener('click', () => {
        form.querySelectorAll('input, textarea, select').forEach(input => {
            if (input.id !== 'fecha_ingreso') {
                if (input.tagName === 'SELECT') input.selectedIndex = 0;
                else input.value = '';
                input.classList.remove('is-invalid');
            }
        });
        form.querySelectorAll('.invalid-feedback').forEach(msg => msg.style.display = 'none');
        if (codigo) codigo.value = '';
        fechaIngreso.value = maxDate; // Reinicia la fecha actual
    });
});
</script>
@endsection
