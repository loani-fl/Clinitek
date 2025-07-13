@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #e8f4fc;
        padding-top: 80px;
    }

    .custom-card {
        background: #fff;
        padding: 2rem;
        max-width: 900px;
        margin: auto;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
        flex-grow: 1;
    }

    .custom-card::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 900px;
        height: 700px;
        background-image: url('/images/logo2.jpg');
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        opacity: 0.05;
        pointer-events: none;
    }

    textarea, input[type="text"] {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .error-message {
        color: red;
        font-size: 0.9rem;
        margin-top: 0.25rem;
    }

    .signature-line {
        margin-top: 30px;
        border-top: 1px solid #000;
        width: 250px;
        text-align: center;
        padding-top: 5px;
        margin-left: auto;
    }

    .buttons {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 20px;
    }

    @media (max-height: 800px) {
        .custom-card {
            padding: 1rem;
        }

        textarea {
            height: 80px;
        }
    }
</style>

<div class="custom-card">
    <h2 class="text-center text-xl font-bold">CLINITEK</h2>

    <!-- Información del médico -->
    <div class="text-center mb-2">
        <strong>Dr. {{ $consulta->medico->nombre ?? 'Nombre no disponible' }}</strong><br>
        <span>Especialista en {{ $consulta->medico->especialidad ?? 'Especialidad no disponible' }}</span><br>
        <hr style="border: none; border-top: 3px solid #0056b3; margin: 0 auto 1rem; width: 100%;">
    </div>

    <!-- Detalles de la consulta -->
    <p><strong>Paciente:</strong> {{ $consulta->paciente->nombre ?? 'Sin asignar' }}</p>
    <p><strong>Fecha:</strong> {{ $consulta->fecha ?? 'Sin fecha' }}</p>

    <!-- Formulario -->
    <form action="{{ route('recetas.store', $consulta->id) }}" method="POST" novalidate>
        @csrf

        <div style="margin-bottom: 5rem;">
            <label for="detalles" style="display: block; font-weight: 600; margin-bottom: 0.25rem;">
                Detalles de prescripción:
            </label>

            <textarea id="detalles" name="detalles" maxlength="500"
                style="width: 100%; height: 200px; padding: 1rem; border: 1px solid #ccc; border-radius: 8px;"
                placeholder="Escribe los detalles aquí" oninput="updateCount()">{{ old('detalles') }}</textarea>

            <div style="text-align: right; font-size: 0.8rem; color: #555;">
                <span id="charCount">0</span>/500 caracteres
            </div>

            @error('detalles')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="signature-line">Firma del Médico</div>

        <a href="{{ route('consultas.show', $consulta->id) }}" 
   style="background-color: #808080; color: black; padding: 10px 16px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;"
   rel="noopener">
   <i class="bi bi-printer"></i> Imprimir
</a>

        <button type="submit"
            style="background-color: #007BFF; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer;">
            Guardar
        </button>
    </form>
</div>

<script>
    function updateCount() {
        const detalles = document.getElementById('detalles');
        const count = document.getElementById('charCount');
        count.textContent = detalles.value.length;
    }

    document.addEventListener('DOMContentLoaded', updateCount);
</script>
@endsection
