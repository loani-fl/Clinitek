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
                <!-- Nombre -->
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

                <!-- Teléfono -->
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

                <!-- Descuento -->
                <div class="col-md-4">
                    <label for="descuento" class="form-label">Descuento (%):</label>
                    <input
                        type="number"
                        name="descuento"
                        id="descuento"
                        class="form-control @error('descuento') is-invalid @enderror"
                        value="{{ old('descuento') }}"
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


                <div class="row mb-3">
                <!-- Página web -->
                    <div class="col-md-4">
                        <label for="pagina_web" class="form-label">Página web (Opcional):</label>
                        <input
                            type="url"
                            name="pagina_web"
                            id="pagina_web"
                            class="form-control @error('pagina_web') is-invalid @enderror"
                            value="{{ old('pagina_web') }}"
                            maxlength="100"
                            placeholder="https://ejemplo.com"
                            pattern="https?://.+"
                            title="Ingrese una URL válida, comenzando con http:// o https://">
                        @error('pagina_web')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <!-- Departamento -->
                <div class="col-md-4">
                    <label for="departamento" class="form-label">Departamento:</label>
                    <select
                        name="departamento"
                        id="departamento"
                        class="form-control @error('departamento') is-invalid @enderror">
                        <option value="">Seleccione un departamento</option>
                        <option value="Atlántida" {{ old('departamento') == 'Atlántida' ? 'selected' : '' }}>Atlántida</option>
                        <option value="Choluteca" {{ old('departamento') == 'Choluteca' ? 'selected' : '' }}>Choluteca</option>
                        <option value="Colón" {{ old('departamento') == 'Colón' ? 'selected' : '' }}>Colón</option>
                        <option value="Comayagua" {{ old('departamento') == 'Comayagua' ? 'selected' : '' }}>Comayagua</option>
                        <option value="Copán" {{ old('departamento') == 'Copán' ? 'selected' : '' }}>Copán</option>
                        <option value="Cortés" {{ old('departamento') == 'Cortés' ? 'selected' : '' }}>Cortés</option>
                        <option value="El Paraíso" {{ old('departamento') == 'El Paraíso' ? 'selected' : '' }}>El Paraíso</option>
                        <option value="Francisco Morazán" {{ old('departamento') == 'Francisco Morazán' ? 'selected' : '' }}>Francisco Morazán</option>
                        <option value="Gracias a Dios" {{ old('departamento') == 'Gracias a Dios' ? 'selected' : '' }}>Gracias a Dios</option>
                        <option value="Intibucá" {{ old('departamento') == 'Intibucá' ? 'selected' : '' }}>Intibucá</option>
                        <option value="Islas de la Bahía" {{ old('departamento') == 'Islas de la Bahía' ? 'selected' : '' }}>Islas de la Bahía</option>
                        <option value="La Paz" {{ old('departamento') == 'La Paz' ? 'selected' : '' }}>La Paz</option>
                        <option value="Lempira" {{ old('departamento') == 'Lempira' ? 'selected' : '' }}>Lempira</option>
                        <option value="Ocotepeque" {{ old('departamento') == 'Ocotepeque' ? 'selected' : '' }}>Ocotepeque</option>
                        <option value="Olancho" {{ old('departamento') == 'Olancho' ? 'selected' : '' }}>Olancho</option>
                        <option value="Santa Bárbara" {{ old('departamento') == 'Santa Bárbara' ? 'selected' : '' }}>Santa Bárbara</option>
                        <option value="Valle" {{ old('departamento') == 'Valle' ? 'selected' : '' }}>Valle</option>
                        <option value="Yoro" {{ old('departamento') == 'Yoro' ? 'selected' : '' }}>Yoro</option>
                    </select>
                    @error('departamento')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Ciudad -->
                    <!-- Municipio -->
                    <div class="col-md-4">
                        <label for="ciudad" class="form-label">Municipio:</label>
                        <select name="ciudad" id="ciudad" class="form-select @error('ciudad') is-invalid @enderror">
                            <option value="">Seleccione un municipio</option>
                            @if(old('departamento'))
                                @foreach($ciudadesPorDepartamento[old('departamento')] as $ciudad)
                                    <option value="{{ $ciudad }}" {{ old('ciudad') == $ciudad ? 'selected' : '' }}>
                                        {{ $ciudad }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('ciudad')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                    <div class="row mb-3">
                <!-- Dirección específica -->
                <div class="col-md-4">
                    <label for="direccion" class="form-label">Dirección específica:</label>
                    <textarea
                        name="direccion"
                        id="direccion"
                        rows="3"
                        maxlength="255"
                        class="form-control @error('direccion') is-invalid @enderror"
                        placeholder="Ej: Barrio El Centro, Casa #45, frente a la escuela">{{ old('direccion') }}</textarea>
                    @error('direccion')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Descripción -->
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
            </div>


                <!-- Foto -->
                <div class="col-md-4">
                    <label for="foto" class="form-label">Foto:</label>

                    <div class="mb-2">
                        <img id="fotoPreview"
                             src="{{ session('foto_temporal') ? asset('storage/' . session('foto_temporal')) : '' }}"
                             alt="Foto seleccionada"
                             class="img-thumbnail"
                             style="max-width: 120px; {{ session('foto_temporal') ? '' : 'display:none;' }}">
                    </div>

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
        const specialKeys = ['Backspace', 'Tab', 'ArrowLeft', 'ArrowRight', 'Delete', 'Home', 'End'];

        // Validación del teléfono
        document.getElementById('telefono').addEventListener('keydown', function(e){
            if(specialKeys.includes(e.key)) return;
            if(!/[0-9]/.test(e.key) || this.value.length >= 8) e.preventDefault();
        });

        // Validación del nombre
        document.getElementById('nombre').addEventListener('keydown', function(e){
            if(specialKeys.includes(e.key)) return;
            if(!/^[a-zA-Z\s\-]$/.test(e.key)) e.preventDefault();
        });

        // Validación de dirección
        document.getElementById('direccion').addEventListener('keydown', function(e){
            if(specialKeys.includes(e.key)) return;
            if(!/^[a-zA-Z0-9\s.,#]$/.test(e.key)) e.preventDefault();
        });

        // Validación de descripción
        document.getElementById('descripcion').addEventListener('keydown', function(e){
            if(specialKeys.includes(e.key)) return;
            if(!/^[a-zA-Z0-9\s.,%]$/.test(e.key)) e.preventDefault();
        });

        // Botón Limpiar formulario
        document.getElementById('btnLimpiar').addEventListener('click', function(){
            const form = this.closest('form');
            form.reset();
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            const fotoPreview = document.getElementById('fotoPreview');
            if(fotoPreview){
                fotoPreview.src = '';
                fotoPreview.style.display = 'none';
            }
        });

        // Previsualización de foto
        const fotoInput = document.getElementById('foto');
        const fotoPreview = document.getElementById('fotoPreview');
        fotoInput.addEventListener('change', function(e){
            const file = e.target.files[0];
            if(file){
                const reader = new FileReader();
                reader.onload = function(ev){
                    fotoPreview.src = ev.target.result;
                    fotoPreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                fotoPreview.src = '';
                fotoPreview.style.display = 'none';
            }
        });

        // Cambio dinámico de ciudades según departamento
        const ciudadesPorDepartamento = @json($ciudadesPorDepartamento);
        document.getElementById('departamento').addEventListener('change', function(){
            const depto = this.value;
            const ciudadSelect = document.getElementById('ciudad');
            ciudadSelect.innerHTML = '<option value="">Seleccione un municipio</option>';
            if(ciudadesPorDepartamento[depto]){
                ciudadesPorDepartamento[depto].forEach(function(ciudad){
                    const option = document.createElement('option');
                    option.value = ciudad;
                    option.textContent = ciudad;
                    ciudadSelect.appendChild(option);
                });
            }
        });
    </script>

@endsection


