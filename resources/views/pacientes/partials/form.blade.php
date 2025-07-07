@csrf

<h5 class="mb-3 text-dark fw-bold">Datos básicos y contacto</h5>

<div class="row mb-3">
<div class="col-md-3">
    <label for="nombre" class="form-label">Nombre(s): <span class="text-danger">*</span></label>
    <input
        type="text"
        name="nombre"
        id="nombre"
        maxlength="30"
        required
        pattern="[A-Za-z\s]+"
        title="Solo letras y espacios, máximo 30 caracteres"
        class="form-control @error('nombre') is-invalid @enderror"
        value="{{ old('nombre', $paciente->nombre ?? '') }}">
    @error('nombre') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
</div>

<div class="col-md-3">
    <label for="apellidos" class="form-label">Apellidos: <span class="text-danger">*</span></label>
    <input
        type="text"
        name="apellidos"
        id="apellidos"
        maxlength="30"
        required
        pattern="[A-Za-z\s]+"
        title="Solo letras y espacios, máximo 30 caracteres"
        class="form-control @error('apellidos') is-invalid @enderror"
        value="{{ old('apellidos', $paciente->apellidos ?? '') }}">
    @error('apellidos') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
</div>


    <div class="col-md-3">
        <label for="identidad" class="form-label">Identidad: <span class="text-danger">*</span></label>
        <input
            type="text"
            name="identidad"
            id="identidad"
            maxlength="13"
            required
            pattern="\d{13}"
            title="Debe contener 13 dígitos numéricos"
            class="form-control @error('identidad') is-invalid @enderror"
            value="{{ old('identidad', $paciente->identidad ?? '') }}">
        @error('identidad') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
    @php use Carbon\Carbon; @endphp

    <div class="col-md-3">
        <label for="fecha_nacimiento" class="form-label">
            Fecha de nacimiento: <span class="text-danger">*</span>
        </label>
        <input
            type="date"
            name="fecha_nacimiento"
            id="fecha_nacimiento"
            required
            class="form-control @error('fecha_nacimiento') is-invalid @enderror"
            value="{{ old('fecha_nacimiento', isset($paciente->fecha_nacimiento) ? Carbon::parse($paciente->fecha_nacimiento)->format('Y-m-d') : '') }}">
        @error('fecha_nacimiento')
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>


<div class="row mb-3">
    <div class="col-md-3">
        <label for="telefono" class="form-label">Teléfono: <span class="text-danger">*</span></label>
        <input
            type="tel"
            name="telefono"
            id="telefono"
            maxlength="8"
            required
            pattern="\d{8}"
            title="Debe contener 8 dígitos numéricos"
            class="form-control @error('telefono') is-invalid @enderror"
            value="{{ old('telefono', $paciente->telefono ?? '') }}">
        @error('telefono') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label for="correo" class="form-label">Correo: <span class="text-danger">*</span></label>
        <input
            type="email"
            name="correo"
            id="correo"
            maxlength="55"
            required
            class="form-control @error('correo') is-invalid @enderror"
            value="{{ old('correo', $paciente->correo ?? '') }}">
        @error('correo') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label for="direccion" class="form-label">Dirección: <span class="text-danger">*</span></label>
        <textarea
            name="direccion"
            id="direccion"
            rows="2"
            maxlength="300"
            required
            class="form-control @error('direccion') is-invalid @enderror">{{ old('direccion', $paciente->direccion ?? '') }}</textarea>
        @error('direccion') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label for="tipo_sangre" class="form-label">Tipo de Sangre:</label>
        <select
            name="tipo_sangre"
            id="tipo_sangre"
            class="form-select @error('tipo_sangre') is-invalid @enderror">
            <option value="">Seleccione...</option>
            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $tipo)
                <option value="{{ $tipo }}" {{ old('tipo_sangre', $paciente->tipo_sangre ?? '') == $tipo ? 'selected' : '' }}>
                    {{ $tipo }}
                </option>
            @endforeach
        </select>
        @error('tipo_sangre') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-3">
        <label for="genero" class="form-label">Género: <span class="text-danger">*</span></label>
        <select
            name="genero"
            id="genero"
            required
            class="form-select @error('genero') is-invalid @enderror">
            <option value="">Seleccione...</option>
            <option value="Femenino" {{ old('genero', $paciente->genero ?? '') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
            <option value="Masculino" {{ old('genero', $paciente->genero ?? '') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
            <option value="Otro" {{ old('genero', $paciente->genero ?? '') == 'Otro' ? 'selected' : '' }}>Otro</option>
        </select>
        @error('genero') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
</div>

<h5 class="mt-4 mb-3 text-dark fw-bold">Datos clínicos</h5>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="padecimientos" class="form-label">Padecimientos:</label>
        <textarea name="padecimientos" id="padecimientos" rows="2" maxlength="200" required
                  class="form-control @error('padecimientos') is-invalid @enderror">{{ old('padecimientos', $paciente->padecimientos ?? '') }}</textarea>
        @error('padecimientos') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="medicamentos" class="form-label">Medicamentos que consume:</label>
        <textarea name="medicamentos" id="medicamentos" rows="2" maxlength="200" required
                  class="form-control @error('medicamentos') is-invalid @enderror">{{ old('medicamentos', $paciente->medicamentos ?? '') }}</textarea>
        @error('medicamentos') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="historial_clinico" class="form-label">Historial clínico:</label>
        <textarea name="historial_clinico" id="historial_clinico" rows="2" maxlength="200" required
                  class="form-control @error('historial_clinico') is-invalid @enderror">{{ old('historial_clinico', $paciente->historial_clinico ?? '') }}</textarea>
        @error('historial_clinico') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="alergias" class="form-label">Alergias conocidas:</label>
        <textarea name="alergias" id="alergias" rows="2" maxlength="200" required
                  class="form-control @error('alergias') is-invalid @enderror">{{ old('alergias', $paciente->alergias ?? '') }}</textarea>
        @error('alergias') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
</div>

<div class="mb-4">
    <label for="historial_quirurgico" class="form-label">Historial quirúrgico:</label>
    <textarea name="historial_quirurgico" id="historial_quirurgico" rows="2" maxlength="200"
              class="form-control @error('historial_quirurgico') is-invalid @enderror">{{ old('historial_quirurgico', $paciente->historial_quirurgico ?? '') }}</textarea>
    @error('historial_quirurgico') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const soloLetrasEspacios = (event) => {
            const char = String.fromCharCode(event.which);
            if (!/[a-zA-Z\s]/.test(char)) {
                event.preventDefault();
            }
        };

        const soloNumeros = (event) => {
            const char = String.fromCharCode(event.which);
            if (!/[0-9]/.test(char)) {
                event.preventDefault();
            }
        };

        document.getElementById('nombre').addEventListener('keypress', soloLetrasEspacios);
        document.getElementById('apellidos').addEventListener('keypress', soloLetrasEspacios);
        document.getElementById('identidad').addEventListener('keypress', soloNumeros);
        document.getElementById('telefono').addEventListener('keypress', soloNumeros);

        // Bloquear pegar caracteres inválidos
        ['nombre', 'apellidos'].forEach(id => {
            document.getElementById(id).addEventListener('paste', e => {
                const paste = (e.clipboardData || window.clipboardData).getData('text');
                if (/[^a-zA-Z\s]/.test(paste)) e.preventDefault();
            });
        });

        ['identidad', 'telefono'].forEach(id => {
            document.getElementById(id).addEventListener('paste', e => {
                const paste = (e.clipboardData || window.clipboardData).getData('text');
                if (/[^0-9]/.test(paste)) e.preventDefault();
            });
        });
    });
</script>
