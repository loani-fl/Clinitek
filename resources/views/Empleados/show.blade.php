@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #e8f4fc;
    }
    .custom-card {
        max-width: 97%;
        background-color: #f0faff;
        border-color: #91cfff;
    }
    label {
        font-size: 0.85rem;
    }
    input, select, textarea {
        font-size: 0.85rem !important;
    }
    .input-corto {
        width: 100% !important;
    }
</style>

<div class="d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 56px);">
    <div class="card custom-card shadow-sm border rounded-4 w-100">
        <div class="card-header bg-primary text-white py-2">
            <h4 class="mb-0">Detalles del empleado</h4>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @php
                    $inputClass = 'form-control form-control-sm input-corto';
                @endphp

                <div class="col-md-4">
                    <label class="form-label">Nombres</label>
                    <input type="text" value="{{ $empleado->nombres }}" class="{{ $inputClass }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Apellidos</label>
                    <input type="text" value="{{ $empleado->apellidos }}" class="{{ $inputClass }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Identidad</label>
                    <input type="text" value="{{ $empleado->identidad }}" class="{{ $inputClass }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Correo</label>
                    <input type="email" value="{{ $empleado->correo }}" class="{{ $inputClass }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Teléfono</label>
                    <input type="text" value="{{ $empleado->telefono }}" class="{{ $inputClass }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Estado Civil</label>
                    <input type="text" value="{{ $empleado->estado_civil }}" class="{{ $inputClass }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Género</label>
                    <input type="text" value="{{ $empleado->genero }}" class="{{ $inputClass }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Fecha de Ingreso</label>
                    <input type="date" value="{{ $empleado->fecha_ingreso }}" class="{{ $inputClass }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Fecha de Nacimiento</label>
                    <input type="date" value="{{ $empleado->fecha_nacimiento }}" class="{{ $inputClass }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Salario</label>
                    <input type="text" value="{{ $empleado->salario }}" class="{{ $inputClass }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Área</label>
                    <input type="text" value="{{ $empleado->area }}" class="{{ $inputClass }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Turno Asignado</label>
                    <input type="text" value="{{ $empleado->turno_asignado }}" class="{{ $inputClass }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Puesto</label>
                    <input type="text" value="{{ $empleado->puesto->nombre }}" class="{{ $inputClass }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Estado</label>
                    <input type="text" value="{{ $empleado->estado }}" class="{{ $inputClass }}" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Dirección</label>
                    <textarea rows="3" class="{{ $inputClass }}" style="resize: vertical;" readonly>{{ $empleado->direccion }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Observaciones</label>
                    <textarea rows="3" class="{{ $inputClass }}" style="resize: vertical;" readonly>{{ $empleado->observaciones }}</textarea>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-end">
                <a href="{{ route('empleados.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left-circle"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('textarea').forEach(function (el) {
            el.style.overflow = 'hidden';
            el.addEventListener('input', function () {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
            el.dispatchEvent(new Event('input'));
        });
    });
</script>
@endpush
