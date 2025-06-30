@extends('layouts.app')

@section('content')

    <!-- Barra de navegación superior -->
    <div class="header d-flex justify-content-between align-items-center px-3 py-2"
         style="background-color: #007BFF; position: sticky; top: 0; z-index: 1030;">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek"
                 style="height: 40px; width: auto; margin-right: 6px;">
            <span class="fw-bold text-white" style="font-size: 1.5rem;">Clinitek</span>
        </div>
        <div class="d-flex gap-3 flex-wrap">
            <a href="{{ route('puestos.create') }}" class="text-decoration-none text-white fw-semibold">Crear puesto</a>
            <a href="{{ route('empleado.create') }}" class="text-decoration-none text-white fw-semibold">Registrar empleado</a>
            <a href="{{ route('medicos.create') }}" class="text-decoration-none text-white fw-semibold">Registrar médico</a>
        </div>
    </div>

    <!-- Estilo del formulario -->
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
            background-image: url('{{ asset('images/logo2.jpg') }}');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.15;
            transform: translate(-50%, -50%);
            pointer-events: none;
            z-index: 0;
        }
    </style>

    <!-- Contenido principal -->
    <div class="custom-card">
        <div class="mb-4 text-center" style="border-bottom: 3px solid #007BFF;">
            <h2 class="fw-bold text-black mb-0">Editar paciente</h2>
        </div>

        <!-- Formulario -->
        <form id="formPaciente"
              action="{{ route('pacientes.update', $paciente->id) }}"
              method="POST"
              class="needs-validation"
              novalidate>
            @csrf
            @method('PUT')

            {{-- Aquí se reutiliza el formulario compartido --}}
            @include('pacientes.partials.form', ['paciente' => $paciente])

            <div class="d-flex justify-content-center gap-3 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Guardar Cambios
                </button>
                <button type="button" id="btnRestablecer" class="btn btn-warning">
                    <i class="bi bi-arrow-counterclockwise"></i> Restablecer
                </button>

                <a href="{{ route('pacientes.index') }}" class="btn btn-success">
                    <i class="bi bi-arrow-left"></i> Regresar
                </a>
            </div>
        </form>
    </div>

    <!-- Script para limpiar campos si el botón existe -->
    <script>
        document.getElementById('btnLimpiar')?.addEventListener('click', () => {
            const form = document.getElementById('formPaciente');
            form.reset();

            form.querySelectorAll('input, select, textarea').forEach(el => {
                el.classList.remove('is-invalid', 'is-valid');
            });

            form.querySelectorAll('.invalid-feedback').forEach(el => {
                el.textContent = '';
                el.style.display = 'none';
            });
        });
    </script>

    <script>
        document.getElementById('btnRestablecer').addEventListener('click', () => {
            const form = document.getElementById('formPaciente');
            form.reset();

            // Opcional: quita las clases de validación
            form.querySelectorAll('input, select, textarea').forEach(el => {
                el.classList.remove('is-invalid', 'is-valid');
            });

            form.querySelectorAll('.invalid-feedback').forEach(el => {
                el.textContent = '';
                el.style.display = 'none';
            });
            window.scrollTo({ top: 0, behavior: 'smooth' });

        });
    </script>

@endsection
