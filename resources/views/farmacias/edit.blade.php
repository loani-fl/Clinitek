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

        <form id="formularioFarmacia" action="{{ route('farmacias.update', $farmacia->id) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="nombre" class="form-label">Nombre: <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" id="nombre"
                           class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre', $farmacia->nombre) }}"
                           required maxlength="50"
                           pattern="^[\pL\s\-]+$"
                           title="Solo letras, espacios y guiones">
                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label for="telefono" class="form-label">Teléfono: <span class="text-danger">*</span></label>
                    <input type="tel" name="telefono" id="telefono"
                           class="form-control @error('telefono') is-invalid @enderror"
                           value="{{ old('telefono', $farmacia->telefono) }}"
                           required maxlength="8"
                           pattern="^\d{8}$"
                           inputmode="numeric"
                           title="Debe ingresar exactamente 8 dígitos numéricos.">
                    @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label for="descuento" class="form-label">Descuento (%):</label>
                    <input type="number" name="descuento" id="descuento"
                           class="form-control @error('descuento') is-invalid @enderror"
                           value="{{ old('descuento', $farmacia->descuento) }}"
                           min="0" max="100" step="0.1" placeholder="Ej: 10.5">
                    @error('descuento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="horario" class="form-label">Horario:</label>
                    <input type="text" name="horario" id="horario"
                           class="form-control @error('horario') is-invalid @enderror"
                           value="{{ old('horario', $farmacia->horario) }}"
                           maxlength="200"
                           placeholder="Ejemplo: Lun a Vie 8:00 AM - 6:00 PM">
                    @error('horario')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label for="pagina_web" class="form-label">Página web: (Opcional)</label>
                    <input type="url" name="pagina_web" id="pagina_web"
                           class="form-control @error('pagina_web') is-invalid @enderror"
                           value="{{ old('pagina_web', $farmacia->pagina_web) }}"
                           maxlength="100"
                           placeholder="https://ejemplo.com">
                    @error('pagina_web')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label for="ubicacion" class="form-label">Ubicación:</label>
                    <textarea name="ubicacion" id="ubicacion" rows="2"
                              class="form-control @error('ubicacion') is-invalid @enderror"
                              maxlength="255"
                              style="resize: vertical;"
                              placeholder="Dirección o referencia">{{ old('ubicacion', $farmacia->ubicacion) }}</textarea>
                    @error('ubicacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-8">
                    <label for="descripcion" class="form-label">Descripción:</label>
                    <textarea name="descripcion" id="descripcion" rows="3"
                              class="form-control @error('descripcion') is-invalid @enderror"
                              maxlength="200"
                              placeholder="Descripción breve de la farmacia">{{ old('descripcion', $farmacia->descripcion) }}</textarea>
                    @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label for="foto" class="form-label">Foto:</label>

                    @if ($farmacia->foto)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $farmacia->foto) }}" alt="Foto actual" class="img-thumbnail" style="max-width: 120px;">
                        </div>
                    @endif

                    <input
                        type="file"
                        name="foto"
                        id="foto"
                        accept=".jpg,.jpeg,.png,.webp"
                        class="form-control @error('foto') is-invalid @enderror">

                    @error('foto')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="d-flex justify-content-center gap-3 mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save"></i> Guardar Cambios
                </button>

                <button type="reset" class="btn btn-warning" id="btnResetear">
                    <i class="bi bi-arrow-counterclockwise"></i> Restablecer
                </button>

                <a href="{{ route('farmacias.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Cancelar
                </a>
            </div>
        </form>
    </div>

    <script>
        // Restringir números en nombre
        document.getElementById("nombre").addEventListener("input", function(e) {
            this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s\-]/g, '');
        });

        // Solo números en teléfono
        document.getElementById("telefono").addEventListener("input", function(e) {
            this.value = this.value.replace(/\D/g, '').slice(0, 8);
        });
    </script>

@endsection
