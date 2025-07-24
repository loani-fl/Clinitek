@extends('layouts.app')

@section('content')
<style>
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
        opacity: 0.1;
        transform: translate(-50%, -50%);
        pointer-events: none;
        z-index: 0;
    }

    .custom-card {
        max-width: 900px;
        background-color: #fff;
        border-color: #91cfff;
        position: relative;
        overflow: hidden;
        margin: 2rem auto;
        padding: 1rem 2rem;
        border: 1px solid #91cfff;
        border-radius: 12px;
    }

    /* Campos con ancho según máximo caracteres permitidos */
    #titulo {
        width: 100%;
        max-width: calc(60ch + 2rem);
        min-width: 250px;
        box-sizing: border-box;
    }

    #descripcion,
    #tratamiento,
    #observaciones {
        width: 100%;
        max-width: calc(400ch + 2rem);
        min-width: 300px;
        box-sizing: border-box;
    }

    label {
        font-size: 0.9rem;
    }

    input, select, textarea {
        font-size: 0.9rem !important;
    }
</style>

<div class="card custom-card shadow-sm border rounded-4 mx-auto mt-4 position-relative" style="z-index:1;">
    <div class="card-header text-center py-2" style="background-color: #fff; border-bottom: 4px solid #0d6efd;">
        <h5 class="mb-0 fw-bold text-dark" style="font-size: 2.25rem;">Editar diagnóstico</h5>
    </div>

    <div class="card-body px-4 py-3">

        <!-- Información paciente y consulta -->
        <div class="mb-4 p-3 bg-light rounded shadow-sm">
            <h5 class="fw-semibold mb-3">Información del Paciente y Consulta</h5>
            <div class="row mb-2">
                <div class="col-md-4 mb-2"><strong>Nombre Completo:</strong><br>{{ $diagnostico->paciente->nombre }} {{ $diagnostico->paciente->apellidos }}</div>
                <div class="col-md-4 mb-2"><strong>Edad:</strong><br>{{ \Carbon\Carbon::parse($diagnostico->paciente->fecha_nacimiento)->age }} años</div>
                <div class="col-md-4 mb-2"><strong>Identidad:</strong><br>{{ $diagnostico->paciente->identidad }}</div>
                <div class="col-md-4 mb-2"><strong>Fecha de Consulta:</strong><br>{{ \Carbon\Carbon::parse($diagnostico->consulta->fecha)->format('d/m/Y') }}</div>
                <div class="col-md-4 mb-2"><strong>Doctor que atendió:</strong><br>{{ $diagnostico->consulta->medico->nombre }} {{ $diagnostico->consulta->medico->apellidos }}</div>
            </div>
        </div>

        <form id="formDiagnostico" action="{{ route('diagnosticos.update', $diagnostico->id) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <input type="hidden" name="paciente_id" value="{{ $diagnostico->paciente_id }}">
            <input type="hidden" name="consulta_id" value="{{ $diagnostico->consulta_id }}">

            <div class="mb-3">
                <label for="titulo" class="form-label">Título <span class="text-danger">*</span></label>
                <input type="text"
                       name="titulo"
                       id="titulo"
                       class="form-control @error('titulo') is-invalid @enderror"
                       value="{{ old('titulo', $diagnostico->titulo) }}"
                       maxlength="60"
                       required>
                @error('titulo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
                <textarea name="descripcion"
                          id="descripcion"
                          rows="3"
                          class="form-control @error('descripcion') is-invalid @enderror"
                          maxlength="400"
                          required>{{ old('descripcion', $diagnostico->descripcion) }}</textarea>
                @error('descripcion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="tratamiento" class="form-label">Tratamiento <span class="text-danger">*</span></label>
                <textarea name="tratamiento"
                          id="tratamiento"
                          rows="3"
                          class="form-control @error('tratamiento') is-invalid @enderror"
                          maxlength="400"
                          required>{{ old('tratamiento', $diagnostico->tratamiento) }}</textarea>
                @error('tratamiento')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="observaciones" class="form-label">Observaciones (Opcionales)</label>
                <textarea name="observaciones"
                          id="observaciones"
                          rows="3"
                          class="form-control @error('observaciones') is-invalid @enderror"
                          maxlength="400">{{ old('observaciones', $diagnostico->observaciones) }}</textarea>
                @error('observaciones')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-center gap-3 mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-pencil-square"></i> Actualizar diagnóstico
                </button>

                <button type="button" id="btnRestablecer" class="btn btn-warning">
                    <i class="bi bi-arrow-counterclockwise"></i> Restablecer
                </button>

                <a href="{{ route('diagnosticos.show', $diagnostico->id) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Cancelar
                </a>

            </div>
        </form>

        @php
            $valoresOriginales = session()->pull('diagnostico_original', [
                'titulo' => $diagnostico->titulo,
                'descripcion' => $diagnostico->descripcion,
                'tratamiento' => $diagnostico->tratamiento,
                'observaciones' => $diagnostico->observaciones,
            ]);
        @endphp

        <script>
            const valoresOriginales = @json($valoresOriginales);

            document.getElementById('btnRestablecer').addEventListener('click', function () {
                document.getElementById('titulo').value = valoresOriginales.titulo;
                document.getElementById('descripcion').value = valoresOriginales.descripcion;
                document.getElementById('tratamiento').value = valoresOriginales.tratamiento;
                document.getElementById('observaciones').value = valoresOriginales.observaciones;

                const form = document.getElementById('formDiagnostico');
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
            });
        </script>
    </div>
</div>
@endsection
