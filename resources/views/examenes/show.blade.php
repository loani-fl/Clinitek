@extends('layouts.app')

@section('content')
    <style>
        #ordenes-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px; /* espacio entre tarjetas */
        }

        .orden-card {
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 12px;
            cursor: pointer;
            width: calc(50% - 15px); /* dos por fila, descontando gap */
            box-sizing: border-box;
            transition: box-shadow 0.3s ease;
        }

        .orden-card:hover {
            box-shadow: 0 0 8px rgba(0,0,0,0.15);
        }

    </style>

    <div class="container">
        <h2>Historial de examenes: {{ $paciente->nombre }} {{ $paciente->apellidos }}</h2>

        {{-- Filtro por fecha --}}
        <div class="mb-3" style="max-width: 220px;">
            <label for="filtroFecha" class="form-label"></label>
            <input type="date" id="filtroFecha" class="form-control form-control-sm" />
        </div>

        <div id="ordenes-container">
            @forelse($ordenes as $orden)
                <div class="orden-card" data-fecha="{{ \Carbon\Carbon::parse($orden->created_at)->format('Y-m-d') }}" data-bs-toggle="modal" data-bs-target="#modalOrden" data-orden-id="{{ $orden->id }}">
                    <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($orden->created_at)->format('d/m/Y H:i') }}<br>
                    <small>Creada por: {{ $orden->usuario->name ?? 'N/A' }}</small>
                </div>
            @empty
                <p>No hay órdenes registradas para este paciente.</p>
            @endforelse
        </div>

        {{-- Modal para mostrar orden completa --}}
        <div class="modal fade" id="modalOrden" tabindex="-1" aria-labelledby="modalOrdenLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalOrdenLabel">Detalle de Orden de Examen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body" id="modalOrdenBody">
                        {{-- Contenido cargado con JS --}}
                        <p>Cargando...</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="btnImprimir">Imprimir</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filtroFecha = document.getElementById('filtroFecha');
            const ordenesContainer = document.getElementById('ordenes-container');
            const modalOrden = document.getElementById('modalOrden');
            const modalOrdenBody = document.getElementById('modalOrdenBody');
            const btnImprimir = document.getElementById('btnImprimir');
            let ordenes = Array.from(ordenesContainer.children);

            // Filtrar órdenes por fecha
            filtroFecha.addEventListener('change', () => {
                const fechaFiltro = filtroFecha.value; // formato YYYY-MM-DD
                ordenes.forEach(div => {
                    if (!fechaFiltro || div.dataset.fecha === fechaFiltro) {
                        div.style.display = 'block';
                    } else {
                        div.style.display = 'none';
                    }
                });
            });

            // Evento para abrir modal con detalle de orden
            ordenes.forEach(div => {
                div.addEventListener('click', function() {
                    const ordenId = this.dataset.ordenId;
                    // Llamada AJAX para obtener detalle orden
                    fetch(`/ordenes/${ordenId}`)
                        .then(response => response.text())
                        .then(html => {
                            modalOrdenBody.innerHTML = html;
                        })
                        .catch(() => {
                            modalOrdenBody.innerHTML = '<p>Error cargando la orden.</p>';
                        });
                });
            });

            // Botón imprimir abre ventana de impresión con contenido modal
            btnImprimir.addEventListener('click', () => {
                const contenido = modalOrdenBody.innerHTML;
                const ventana = window.open('', '_blank');
                ventana.document.write('<html><head><title>Imprimir Orden</title>');
                ventana.document.write('<style>body{font-family: Arial,sans-serif; padding: 20px;} </style>');
                ventana.document.write('</head><body>');
                ventana.document.write(contenido);
                ventana.document.write('</body></html>');
                ventana.document.close();
                ventana.focus();
                ventana.print();
                ventana.close();
            });
        });
    </script>
@endsection
