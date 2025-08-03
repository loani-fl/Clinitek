@extends('layouts.app')

@section('content')
<style>
    html, body {
        overflow-x: hidden;
        margin: 0;
        padding: 0;
        max-width: 100%;
    }

    .container {
        padding-left: 15px;
        padding-right: 15px;
        max-width: 100%;
        overflow-x: hidden;
    }

    .custom-card {
        max-width: 1000px;
        width: 100%;
        padding-left: 20px;
        padding-right: 20px;
        margin-left: auto;
        margin-right: auto;
        box-sizing: border-box;
        position: relative;
        background-color: #fff;
        border: 1px solid #91cfff;
        border-radius: 12px;
        overflow: hidden;
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

    input, select, textarea {
        max-width: 100%;
        box-sizing: border-box;
        font-size: 0.9rem !important;
    }

    .valid-feedback {
        display: block;
        font-size: 0.75rem;
        color: #198754;
    }

    .form-control.is-valid {
        border-color: #198754;
        padding-right: 2.3rem;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' stroke='%23198754' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' class='bi bi-check-lg' viewBox='0 0 16 16'%3e%3cpath d='M13 4.5 6 11.5 3 8.5'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.6rem center;
        background-size: 0.9rem 0.9rem;
    }

    label.is-invalid {
        color: #dc3545;
        font-weight: 600;
    }
</style>

<div class="card custom-card shadow-sm border rounded-4 mx-auto w-100 mt-4 position-relative" style="z-index:1;">
    <div class="card-header text-center py-2" style="background-color: #fff; border-bottom: 4px solid #0d6efd;">
        <h5 class="mb-0 fw-bold text-dark" style="font-size: 2.25rem;">Editar diagnóstico</h5>
    </div>

    <div class="card-body px-4 py-3">

        <!-- Información paciente y consulta -->
        <div class="mb-4 p-3 bg-light rounded shadow-sm">
            <h5 class="fw-semibold mb-3">Información del Paciente y Consulta</h5>

            <div class="row gx-3 gy-2">
                <div class="col-md-4">
                    <p class="mb-1"><strong>Nombre Completo:</strong></p>
                    <p class="mb-0">{{ $diagnostico->paciente->nombre }} {{ $diagnostico->paciente->apellidos }}</p>
                </div>
                <div class="col-md-4">
                    <p class="mb-1"><strong>Edad:</strong></p>
                    <p class="mb-0">{{ \Carbon\Carbon::parse($diagnostico->paciente->fecha_nacimiento)->age }} años</p>
                </div>
                <div class="col-md-4">
                    <p class="mb-1"><strong>Identidad:</strong></p>
                    <p class="mb-0">{{ $diagnostico->paciente->identidad }}</p>
                </div>

                <div class="col-md-4">
                    <p class="mb-1"><strong>Fecha de Consulta:</strong></p>
                    <p class="mb-0">{{ \Carbon\Carbon::parse($diagnostico->consulta->fecha)->format('d/m/Y') }}</p>
                </div>
                <div class="col-md-8">
                    <p class="mb-1"><strong>Doctor que atendió:</strong></p>
                    <p class="mb-0">{{ $diagnostico->consulta->medico->nombre }} {{ $diagnostico->consulta->medico->apellidos }}</p>
                </div>
            </div>
        </div>

        <form id="formDiagnostico" action="{{ route('diagnosticos.update', $diagnostico->id) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <input type="hidden" name="paciente_id" value="{{ $diagnostico->paciente_id }}">
            <input type="hidden" name="consulta_id" value="{{ $diagnostico->consulta_id }}">

            <div class="row gx-3 gy-3">
                {{-- Título --}}
                <div class="col-md-6">
                    <label for="titulo" class="form-label">Resumen <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        name="titulo"
                        id="titulo"
                        class="form-control @error('titulo') is-invalid @enderror"
                        value="{{ old('titulo', $diagnostico->titulo) }}"
                        maxlength="60"
                        required
                    >
                    @error('titulo')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Descripción --}}
                <div class="col-md-6">
                    <label for="descripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
                    <textarea
                        name="descripcion"
                        id="descripcion"
                        class="form-control @error('descripcion') is-invalid @enderror"
                        rows="4"
                        maxlength="400"
                        required
                    >{{ old('descripcion', $diagnostico->descripcion) }}</textarea>
                    @error('descripcion')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tratamiento --}}
                <div class="col-md-6">
                    <label for="tratamiento" class="form-label">Tratamiento <span class="text-danger">*</span></label>
                    <textarea
                        name="tratamiento"
                        id="tratamiento"
                        class="form-control @error('tratamiento') is-invalid @enderror"
                        rows="4"
                        maxlength="400"
                        required
                    >{{ old('tratamiento', $diagnostico->tratamiento) }}</textarea>
                    @error('tratamiento')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Observaciones --}}
                <div class="col-md-6">
                    <label for="observaciones" class="form-label">Observaciones (Opcional)</label>
                    <textarea
                        name="observaciones"
                        id="observaciones"
                        class="form-control @error('observaciones') is-invalid @enderror"
                        rows="4"
                        maxlength="400"
                    >{{ old('observaciones', $diagnostico->observaciones) }}</textarea>
                    @error('observaciones')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-center gap-3 mt-4 align-items-center">
                <button type="submit" class="btn btn-primary btn-sm px-4 shadow-sm d-inline-flex align-items-center gap-2" style="font-size: 0.85rem;">
                    <i class="bi bi-pencil-square"></i> Actualizar diagnóstico
                </button>

                <button type="button" id="btnRestablecer" class="btn btn-warning btn-sm px-4 shadow-sm d-inline-flex align-items-center gap-2" style="font-size: 0.85rem;">
                    <i class="bi bi-arrow-counterclockwise"></i> Restablecer
                </button>

                <a href="{{ route('diagnosticos.show', $diagnostico->id) }}" class="btn btn-secondary btn-sm px-4 shadow-sm d-inline-flex align-items-center gap-2" style="font-size: 0.85rem;">
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
