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
    {{-- Botón de inicio --}}
    <a href="{{ route('inicio') }}" class="btn boton-inicio">Inicio</a>

    <div class="factura-header">
        <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek">
        <h1>CLINITEK</h1>
        <h2>FACTURA DE PAGO</h2>
    </div>

    <div class="factura-divider"></div>

    <div class="factura-section">
        <div class="factura-section">
    {{-- Datos comunes --}}
    @if($paciente)
        {{-- Fila 1 --}}
        <div style="display: flex; gap: 1rem; margin-bottom: 0.3rem;">
            <p style="flex: 1; display: flex; align-items: center; margin: 0;">
                <strong style="width: 130px; margin-right: 0.3rem;">Nombre completo:</strong> {{ $paciente->nombre }} {{ $paciente->apellidos }}
            </p>
            <p style="flex: 1; display: flex; align-items: center; margin: 0;">
                <strong style="width: 130px; margin-right: 0.3rem;">Identidad:</strong> {{ $paciente->identidad }}
            </p>
        </div>
    @endif

    @if($pago->metodo_pago === 'efectivo')
        {{-- Fila 2 --}}
        <div style="display: flex; gap: 1rem; margin-bottom: 0.3rem;">
            <p style="flex: 1; display: flex; align-items: center; margin: 0;">
                <strong style="width: 130px; margin-right: 0.3rem;">Método de pago:</strong> {{ ucfirst($pago->metodo_pago) }}
            </p>
            <p style="flex: 1; display: flex; align-items: center; margin: 0;">
                <strong style="width: 130px; margin-right: 0.3rem;">Fecha:</strong> {{ \Carbon\Carbon::parse($pago->fecha)->format('d/m/Y')}}
            </p>
        </div>

        {{-- Fila 3 --}}
        <div style="margin-bottom: 0.3rem;">
            <p style="margin: 0; display: flex; align-items: center;">
                <strong style="width: 130px; margin-right: 0.3rem;">Hora:</strong> {{ \Carbon\Carbon::now('America/Tegucigalpa')->format('h:i A') }}
            </p>
        </div>

    @elseif($pago->metodo_pago === 'tarjeta')
        {{-- Fila 2 --}}
        <div style="display: flex; gap: 1rem; margin-bottom: 0.3rem;">
            <p style="flex: 1; display: flex; align-items: center; margin: 0;">
                <strong style="width: 130px; margin-right: 0.3rem;">Método de pago:</strong> {{ ucfirst($pago->metodo_pago) }}
            </p>
            <p style="flex: 1; display: flex; align-items: center; margin: 0;">
                <strong style="width: 130px; margin-right: 0.3rem;">Nombre del titular:</strong> {{ $pago->nombre_titular ?? 'No disponible' }}
            </p>
        </div>

        {{-- Fila 3 --}}
        <div style="display: flex; gap: 1rem; margin-bottom: 0.3rem;">
            <p style="flex: 1; display: flex; align-items: center; margin: 0;">
                <strong style="width: 130px; margin-right: 0.3rem;">Número de tarjeta:</strong> {{ $pago->numero_tarjeta ?? 'No disponible' }}
            </p>
            <p style="flex: 1; display: flex; align-items: center; margin: 0;">
                <strong style="width: 130px; margin-right: 0.3rem;">Fecha de expiración:</strong> {{ $pago->fecha_expiracion ?? 'No disponible' }}
            </p>
        </div>

        {{-- Fila 4 --}}
        <div style="display: flex; gap: 1rem; margin-bottom: 0.3rem;">
            <p style="flex: 1; display: flex; align-items: center; margin: 0;">
                <strong style="width: 130px; margin-right: 0.3rem;">Fecha:</strong> {{ \Carbon\Carbon::parse($pago->fecha)->format('d/m/Y')}}
            </p>
            <p style="flex: 1; display: flex; align-items: center; margin: 0;">
                <strong style="width: 130px; margin-right: 0.3rem;">Hora:</strong> {{ \Carbon\Carbon::now('America/Tegucigalpa')->format('h:i A') }}
            </p>
        </div>
    @endif

</div>

    </div>

    <div class="factura-divider"></div>

    <table class="factura-table">
        <thead>
            <tr>
                <th>Descripción</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $pago->servicio }}</td>
                <td>L. {{ number_format($pago->cantidad, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="factura-divider"></div>

    <div class="factura-section">
        @php
            $isv = round($pago->cantidad * 0.15, 2);
        @endphp
        <p><strong>ISV (15%):</strong> L. {{ number_format($isv, 2) }}</p>
        <p><strong>Total a pagar:</strong> L. {{ number_format($pago->cantidad + $isv, 2) }}</p>
    </div>

    <div class="factura-divider"></div>

    <div class="factura-footer">
        *** ¡AGRADECEMOS SU PREFERENCIA! ***<br>
        Gracias por confiar en nosotros <strong>CLINITEK</strong>.
    </div>
</div>
@endsection
