@extends('layouts.app')

@section('content')
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
    <a href="{{ route('inicio') }}" class="btn" style="background-color: #6c757d; color: white;">Inicio</a>

    <div class="factura-header">
        <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek">
        <h1>CLINITEK</h1>
        <h2>FACTURA DE PAGO</h2>
    </div>

    <div class="factura-divider"></div>

    <div class="factura-section row">
        {{-- Columna 1 --}}
        <div class="col-md-6">
            @if($paciente)
                <p><strong>Nombre completo:</strong> {{ $paciente->nombre }} {{ $paciente->apellidos }}</p>
            @endif
            <p><strong>Nombre del Titular:</strong> {{ $pago->nombre_titular }}</p>
            <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($pago->fecha)->format('d/m/Y') }}</p>
        </div>

        {{-- Columna 2 --}}
        <div class="col-md-6">
            @if($paciente)
                <p><strong>Identidad:</strong> {{ $paciente->identidad }}</p>
            @endif
            @if($pago->metodo_pago === 'tarjeta')
                <p><strong>Número de Tarjeta:</strong> {{ $pago->numero_tarjeta }}</p>
            @endif
            <p><strong>Hora:</strong> {{ \Carbon\Carbon::now('America/Tegucigalpa')->format('h:i A') }}</p>
        </div>

        {{-- Fila separada para Método de pago e Impuesto --}}
        <div class="col-md-6 mt-2">
            <p><strong>Método de Pago:</strong> {{ ucfirst($pago->metodo_pago) }}</p>
        </div>
        <div class="col-md-6 mt-2">
            @php
                $impuesto = round($pago->cantidad * 0.15, 2);
            @endphp
            <p><strong>Isv (15%):</strong> L. {{ number_format($impuesto, 2) }}</p>
        </div>
    </div>

    <div class="factura-divider"></div>

    <table class="factura-table">
        <thead>
            <tr>
                <th>Descripcion</th>
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
        <p><strong>Total a pagar:</strong> {{ $pago->servicio }}</p>
    </div>

    <div class="factura-divider"></div>

    <div class="factura-footer">
        *** AGRADECEMOS A PREFERENCIA! ***<br>
        Gracias por confiar en nosotros <strong>CLINITEK</strong>.
    </div>
</div>
@endsection
