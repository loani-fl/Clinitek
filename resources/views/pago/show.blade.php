@extends('layouts.app')

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<style>
    .factura-container {
        max-width: 800px;
        margin: 2rem auto;
        border: 1px solid #000;
        padding: 1.5rem;
        font-family: Arial, sans-serif;
        color: #000;
        position: relative;
    }

    .boton-inicio {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background-color: #ccc;
        color: #000;
        border: none;
        padding: 0.4rem 1rem;
        text-decoration: none;
        font-size: 0.9rem;
        border-radius: 4px;
        cursor: pointer;
    }

    .boton-inicio:hover {
        background-color: #bbb;
    }

    .factura-header {
        text-align: center;
        margin-bottom: 1rem;
    }

    .factura-header img {
        height: 80px;
        margin-bottom: 0.5rem;
    }

    .factura-header h1 {
        font-size: 1.8rem;
        margin: 0;
    }

    .factura-header h2 {
        font-size: 1.2rem;
        margin: 0;
    }

    .factura-divider {
        border-top: 1px solid #000;
        margin: 0.8rem 0;
    }

    .factura-section {
        margin-bottom: 1rem;
        font-size: 0.95rem;
    }

    .factura-section strong {
        display: inline-block;
        width: 180px;
    }

    .factura-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
        font-size: 0.95rem;
        table-layout: fixed;
    }

    .factura-table th,
    .factura-table td {
        border: 1px solid #000;
        padding: 0.4rem 0.6rem;
        text-align: left;
        vertical-align: top;
        word-wrap: break-word;
        word-break: break-word;
        white-space: normal;
    }

    .factura-table th:first-child,
    .factura-table td:first-child {
        width: 70%;
    }

    .factura-footer {
        text-align: center;
        margin-top: 2rem;
        font-size: 0.85rem;
    }
</style>

<div class="factura-container">

    <!-- Encabezado -->
    <div class="factura-header" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
        <!-- Lado izquierdo: logo y nombre -->
        <div style="display: flex; align-items: center;">
            <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek" style="height: 60px; margin-right: 10px;">
            <h1 style="margin: 0; font-weight: bold; font-style: italic;">CLINITEK</h1>
        </div>

        <!-- Lado derecho: dirección y título -->
        <div style="text-align: right;">
            <p style="margin: 0;">Uniplaza, Bo. Gualiqueme,<br>Gasolinera Texaco, Frente a, Danlí</p>
            <p style="margin: 5px 0 0 0; font-weight: bold; font-size: 14px;">FACTURA DE PAGO</p>
        </div>
    </div>

    <div class="factura-divider"></div>

   <!-- Datos del paciente, médico y pago en dos columnas -->
<div class="factura-section">
    <!-- Fila 1: Nombre completo y Identidad -->
    <div style="display: flex; gap: 1rem; margin-bottom: 0.3rem;">
        <p style="flex: 1; margin: 0;">
            <strong>Nombre completo:</strong> {{ $paciente->nombre ?? 'N/A' }} {{ $paciente->apellidos ?? '' }}
        </p>
        <p style="flex: 1; margin: 0;">
            <strong>Identidad:</strong> {{ $paciente->identidad ?? 'N/A' }}
        </p>
    </div>

    <!-- Fila 2: Médico y Especialidad -->
    <div style="display: flex; gap: 1rem; margin-bottom: 0.3rem;">
        <p style="flex: 1; margin: 0;">
            <strong>Médico:</strong> {{ $medico->nombre ?? 'N/A' }} {{ $medico->apellidos ?? '' }}
        </p>
        <p style="flex: 1; margin: 0;">
            <strong>Especialidad:</strong> {{ $medico->especialidad ?? 'N/A' }}
        </p>
    </div>

    <!-- Fila 3: Método de pago y Fecha -->
    <div style="display: flex; gap: 1rem; margin-bottom: 0.3rem;">
        <p style="flex: 1; margin: 0;">
            <strong>Método de pago:</strong> {{ ucfirst($pago->metodo_pago) }}
        </p>
        <p style="flex: 1; margin: 0;">
            <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($pago->fecha)->format('d/m/Y') }}
        </p>
    </div>

    <!-- Fila 4: Hora -->
    <div style="display: flex; gap: 1rem; margin-bottom: 0.3rem;">
        <p style="flex: 1; margin: 0;">
            <strong>Hora:</strong> {{ \Carbon\Carbon::now('America/Tegucigalpa')->format('h:i A') }}
        </p>
        <p style="flex: 1; margin: 0;">
            <!-- Columna vacía para mantener la simetría -->
        </p>
    </div>
</div>


    <div class="factura-divider"></div>

    <!-- Tabla con servicio y precio -->
    <table class="factura-table">
    <thead>
        <tr>
            <th>Descripción</th>
            <th>Valor</th>
        </tr>
    </thead>
    <tbody>
    @if($origen === 'consulta')
    <tr>
        <td>Consulta médica</td>
        <td>L. {{ number_format($pago->cantidad, 2) }}</td>
    </tr>
@elseif($origen === 'rayosx')
    @if($examenesConPrecio && count($examenesConPrecio) > 0)
        @foreach($examenesConPrecio as $examen)
            <tr>
                <td>{{ $examen['descripcion'] }}</td>
                <td>L. {{ number_format($examen['precio'], 2) }}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td>Exámenes de Rayos X</td>
            <td>L. {{ number_format($pago->cantidad, 2) }}</td>
        </tr>
    @endif
@endif

    </tbody>
</table>

    <div class="factura-divider"></div>

    <!-- Subtotales -->
    <div class="factura-section">
        @php
            $isv = round($pago->cantidad * 0.15, 2);
            $subtotal = round($pago->cantidad - $isv, 2);
            $total = $subtotal + $isv;
        @endphp

        <p><strong>Subtotal:</strong> L. {{ number_format($subtotal, 2) }}</p>
        <p><strong>ISV (15%):</strong> L. {{ number_format($isv, 2) }}</p>
        <p><strong>Total a pagar:</strong> L. {{ number_format($total, 2) }}</p>
    </div>

    <div class="factura-divider"></div>

    <!-- Pie de factura -->
<div class="factura-footer" style="display: flex; justify-content: space-between; align-items: center; margin-top: 40px;">
    <!-- Botón a la izquierda -->
    @if($origen === 'rayosx')
        <a href="{{ route('rayosx.index') }}" style="
            display: inline-block;
            background-color: #ccc;
            color: black;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            border: 1px solid #999;
        ">Regresar</a>
    @else
        <a href="{{ route('inicio') }}" style="
            display: inline-block;
            background-color: #ccc;
            color: black;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            border: 1px solid #999;
        ">Inicio</a>
    @endif

    <!-- Texto a la derecha -->
    <div style="text-align: right;">
        *** ¡AGRADECEMOS SU PREFERENCIA! ***<br>
        Gracias por confiar en nosotros <strong>CLINITEK</strong>.
    </div>
</div>

</div>
@endsection