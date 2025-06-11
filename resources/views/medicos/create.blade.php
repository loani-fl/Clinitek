@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card p-4 w-100">
        <h2 class="mb-4">Registrar Nuevo Médico</h2>
        <form method="POST" action="{{ route('medicos.store') }}" enctype="multipart/form-data">

        @csrf

            {{-- Información Personal --}}
            <h5 class="mb-3 border-bottom pb-1">Información Personal</h5>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre: <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="apellidos" class="form-label">Apellidos: <span class="text-danger">*</span></label>
                    <input type="text" name="apellidos" class="form-control @error('apellidos') is-invalid @enderror" value="{{ old('apellidos') }}" required>
                    @error('apellidos')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento: <span class="text-danger">*</span></label>
                    <input type="date" name="fecha_nacimiento" class="form-control @error('fecha_nacimiento') is-invalid @enderror" value="{{ old('fecha_nacimiento') }}" required>
                    @error('fecha_nacimiento')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="genero" class="form-label">Género: <span class="text-danger">*</span></label>
                    <select name="genero" class="form-select @error('genero') is-invalid @enderror" required>
                        <option value="">Seleccionar</option>
                        <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                        <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                        <option value="Otro" {{ old('genero') == 'Otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                    @error('genero')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Información Profesional --}}
            <h5 class="mb-3 border-bottom pb-1">Información Profesional</h5>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="especialidad" class="form-label">Especialidad: <span class="text-danger">*</span></label>
                    <input type="text" name="especialidad" class="form-control @error('especialidad') is-invalid @enderror" value="{{ old('especialidad') }}" required>
                    @error('especialidad')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="fecha_ingreso" class="form-label">Fecha de Ingreso: <span class="text-danger">*</span></label>
                    <input type="date" name="fecha_ingreso" class="form-control @error('fecha_ingreso') is-invalid @enderror" value="{{ old('fecha_ingreso') }}" required>
                    @error('fecha_ingreso')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Información de Contacto --}}
            <h5 class="mb-3 border-bottom pb-1">Información de Contacto</h5>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="telefono" class="form-label">Teléfono: <span class="text-danger">*</span></label>
                    <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono') }}" required>
                    @error('telefono')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="correo" class="form-label">Correo Electrónico: <span class="text-danger">*</span></label>
                    <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror" value="{{ old('correo') }}" required>
                    @error('correo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-section">
                <h5 class="section-title">Información Adicional</h5>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Observaciones:</label>
                        <textarea name="observaciones" class="form-control @error('observaciones') is-invalid @enderror" rows="4" placeholder="Información adicional sobre el médico (opcional)">{{ old('observaciones') }}</textarea>
                        @error('observaciones')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Este campo es opcional.</div>
                    </div>
                </div>
            </div>
            {{-- Subida de Foto --}}
            <h5 class="mb-3 border-bottom pb-1">Foto del Médico</h5>
            <div class="mb-3">
                <label for="foto" class="form-label">Foto:</label>
                <input type="file" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                @error('foto')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Puedes arrastrar o seleccionar una imagen en formato JPG, PNG o GIF (máx. 2MB).</div>
            </div>


            {{-- Botones --}}
            <div class="d-flex justify-content-between">
            <a href="{{ route('medicos.index') }}" class="btn btn-primary">
                 <i class="bi bi-arrow-left-circle me-1"></i> Regresar
                </a>

                <button type="submit" class="btn btn-primary">
                        <i class="bi bi-box-arrow-down me-1"></i> Guardar
                </button>

            </div>
        </form>
    </div>
</div>
@endsection
