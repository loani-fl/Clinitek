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
            <h2 class="fw-bold text-black mb-0">Registrar farmacia asociada</h2>
        </div>

        <form action="{{ route('farmacias.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="nombre" class="form-label">Nombre: <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        name="nombre"
                        id="nombre"
                        class="form-control @error('nombre') is-invalid @enderror"
                        value="{{ old('nombre') }}"
                        required
                        maxlength="50"
                        pattern="^[\pL\s\-]+$"
                        title="Solo letras, espacios y guiones">

                    @error('nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="telefono" class="form-label">Teléfono: <span class="text-danger">*</span></label>
                    <input
                        type="tel"
                        name="telefono"
                        id="telefono"
                        class="form-control @error('telefono') is-invalid @enderror"
                        value="{{ old('telefono') }}"
                        required
                        maxlength="8"
                        pattern="^\d{8}$"
                        inputmode="numeric"
                        title="Debe ingresar exactamente 8 dígitos numéricos.">

                    @error('telefono')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="descuento" class="form-label">Descuento (%):</label>
                    <input
                        type="number"
                        name="descuento"
                        id="descuento"
                        class="form-control @error('descuento') is-invalid @enderror"
                        value="{{ old('descuento') }}"
                        min="0"
                        max="100"
                        step="0.1"
                        placeholder="Ej: 10.5"
                        oninput="if(this.value < 0) this.value = 0; if(this.value > 100) this.value = 100;">
                    @error('descuento')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="row mb-3">
                <div class="col-md-4">
                    <label for="horario" class="form-label">Horario:</label>
                    <input
                        type="text"
                        name="horario"
                        id="horario"
                        class="form-control @error('horario') is-invalid @enderror"
                        value="{{ old('horario') }}"
                        maxlength="200"
                        placeholder="Ejemplo: Lun a Vie 8:00 AM - 6:00 PM">
                    @error('horario')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="pagina_web" class="form-label">Página web:(Opcional)</label>
                    <input
                        type="url"
                        name="pagina_web"
                        id="pagina_web"
                        class="form-control @error('pagina_web') is-invalid @enderror"
                        value="{{ old('pagina_web') }}"
                        maxlength="100"
                        placeholder="https://ejemplo.com">
                    @error('pagina_web')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="ubicacion" class="form-label">Ubicación:</label>
                    <textarea
                        name="ubicacion"
                        id="ubicacion"
                        rows="2"
                        maxlength="255"
                        class="form-control @error('ubicacion') is-invalid @enderror"
                        style="resize: vertical;"
                        placeholder="Ciudad, Departamento, País">{{ old('ubicacion') }}</textarea>
                    @error('ubicacion')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>



            <div class="row mb-3">
                <div class="col-md-8">
                    <label for="descripcion" class="form-label">Descripción:</label>
                    <textarea
                        name="descripcion"
                        id="descripcion"
                        class="form-control @error('descripcion') is-invalid @enderror"
                        rows="3"
                        maxlength="200"
                        placeholder="Descripción breve de la farmacia">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="foto" class="form-label">Foto:</label>
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
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Registrar
                </button>
            <button type="button" id="btnLimpiar" class="btn btn-warning">
                <i class="bi bi-trash"></i> Limpiar
            </button>
            <a href="{{ route('farmacias.index') }}" class="btn btn-success">
                <i class="bi bi-arrow-left"></i> Regresar
            </a>
            </div>

        </form>
    </div>
    <script>
        document.getElementById('telefono').addEventListener('keydown', function(e) {
            // Permitir teclas especiales: Backspace, Tab, Arrow keys, Delete, etc.
            const specialKeys = ['Backspace', 'Tab', 'ArrowLeft', 'ArrowRight', 'Delete', 'Home', 'End'];
            if (specialKeys.includes(e.key)) {
                return; // permitir estas teclas
            }

            // Permitir solo números (0-9)
            if (!e.key.match(/[0-9]/)) {
                e.preventDefault(); // bloquear cualquier otra tecla
            }

            // Limitar máximo 8 caracteres
            if (this.value.length >= 8 && !specialKeys.includes(e.key)) {
                e.preventDefault();
            }
        });
    </script>
    <script>
        document.getElementById('nombre').addEventListener('keydown', function(e) {
            // Permitir teclas especiales
            const specialKeys = ['Backspace', 'Tab', 'ArrowLeft', 'ArrowRight', 'Delete', 'Home', 'End'];
            if (specialKeys.includes(e.key)) {
                return; // permitir estas teclas
            }

            // Permitir letras (A-Z, a-z), espacios y guiones
            const regex = /^[a-zA-Z\s\-]$/;

            if (!regex.test(e.key)) {
                e.preventDefault();
            }
        });
    </script>
    <script>
        document.getElementById('btnLimpiar').addEventListener('click', function () {
            const form = this.closest('form');
            form.reset();

            // Limpia manualmente los campos con valores por defecto (como old('') en Blade)
            Array.from(form.querySelectorAll('input, textarea')).forEach(input => {
                if (input.type !== 'hidden' && input.type !== 'submit' && input.type !== 'button') {
                    input.value = '';
                }
            });

            // Elimina posibles mensajes de error visuales (como .is-invalid)
            form.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
        });
    </script>


@endsection


