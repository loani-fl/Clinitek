@extends('layouts.app')

@section('title', 'Detalles del Paciente en Emergencias')

@section('content')
    @php
        use Illuminate\Support\Facades\Storage;
        use Carbon\Carbon;

        $paciente = $emergencia->paciente;
        $mostrarFoto = false;

        if(!$paciente && $emergencia->foto && Storage::exists('public/' . $emergencia->foto)) {
            $mostrarFoto = true;
        }

        if(!$emergencia->edad && $paciente?->fecha_nacimiento) {
            $edad = Carbon::parse($paciente->fecha_nacimiento)->age;
        } else {
            $edad = $emergencia->edad;
        }

        $historial = $paciente
            ? $paciente->emergencias()->where('id', '!=', $emergencia->id)->orderBy('fecha', 'desc')->get()
            : collect();
    @endphp

    <style>
        .custom-card::before {
            content: "";
            position: absolute;
            top: 50%; left: 50%;
            width: 800px; height: 800px;
            background-image: url('/images/logo2.jpg');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.12;
            transform: translate(-50%, -50%);
            pointer-events: none;
            z-index: 0;
        }
        .custom-card {
            max-width: 1000px;
            background-color: #fff;
            border: 1px solid #91cfff;
            border-radius: 12px;
            margin: 1.5rem auto;
            padding: 1.2rem;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        .paciente-img {
            width: 140px; height: 140px;
            object-fit: cover; border-radius: 10px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            display: block;
            margin: 0 auto 1rem auto;
        }
        .info-label { font-weight: 700; font-size: 1rem; color: #003366; display:block; }
        .info-value { font-size: 1.05rem; color: #222; margin-top: 4px; }
        .info-block { padding: 6px 8px; }
        .section-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-top: 1rem;
            margin-bottom: 0.6rem;
            color: #0b5ed7;
            border-bottom: 2px solid #0b5ed7;
            padding-bottom: 4px;
        }
        .vital-label { font-weight: 600; color: #0d6efd; display:block; text-align:center; font-size:1rem; }
        .vital-value { font-size: 1.1rem; text-align:center; font-weight: 500; margin-top: 3px; }
        @media (max-width: 768px) {
            .paciente-img { width: 110px; height: 110px; }
            .info-value { font-size: 1rem; }
        }
    </style>

    <div class="container mt-3">
        <div class="card custom-card shadow-sm border rounded-4">
            <div class="card-header text-center py-3" style="background-color:#fff; border-bottom:4px solid #0d6efd;">
                <h3 class="mb-0 fw-bold text-dark">Detalles del Paciente en Emergencias</h3>
            </div>

            <div class="card-body px-3 py-3">
                {{-- Foto --}}
                @if($mostrarFoto)
                    <img src="{{ asset('storage/' . $emergencia->foto) }}" alt="Foto del paciente"
                         class="paciente-img" data-bs-toggle="modal" data-bs-target="#fotoModal">
                @endif

                {{-- Datos del paciente --}}
                <div class="row gy-2">
                    <div class="col-md-3 info-block">
                        <span class="info-label">Nombres:</span>
                        <div class="info-value">{{ $paciente->nombre ?? $emergencia->nombre ?? '—' }}</div>
                    </div>
                    <div class="col-md-3 info-block">
                        <span class="info-label">Apellidos:</span>
                        <div class="info-value">{{ $paciente->apellido ?? $emergencia->apellido ?? '—' }}</div>
                    </div>
                    <div class="col-md-3 info-block">
                        <span class="info-label">Identidad:</span>
                        <div class="info-value">{{ $paciente->identidad ?? '—' }}</div>
                    </div>
                    <div class="col-md-3 info-block">
                        <span class="info-label">Edad:</span>
                        <div class="info-value">{{ $edad ?? '—' }}</div>
                    </div>
                    <div class="col-md-3 info-block">
                        <span class="info-label">Sexo:</span>
                        <div class="info-value">{{ $paciente->genero ?? $emergencia->genero ?? '—' }}</div>
                    </div>
                    <div class="col-md-3 info-block">
                        <span class="info-label">Teléfono:</span>
                        <div class="info-value">{{ $paciente->telefono ?? $emergencia->telefono ?? '—' }}</div>
                    </div>
                    <div class="col-md-3 info-block">
                        <span class="info-label">Tipo de Sangre:</span>
                        <div class="info-value">{{ $paciente->tipo_sangre ?? $emergencia->tipo_sangre ?? '—' }}</div>
                    </div>
                    <div class="col-md-6 info-block">
                        <span class="info-label">Dirección:</span>
                        <textarea class="form-control" rows="2" readonly style="resize:none; background-color:#f8f9fa;">{{ $paciente->direccion ?? $emergencia->direccion ?? '—' }}</textarea>
                    </div>

                    {{-- Detalles de Emergencia --}}
                    <div class="col-12"><div class="section-title">Detalles de Emergencia</div></div>
                    <div class="col-md-3 info-block">
                        <span class="info-label">Fecha:</span>
                        <div class="info-value">{{ \Carbon\Carbon::parse($emergencia->fecha)->format('d/m/Y') }}</div>
                    </div>
                    <div class="col-md-3 info-block">
                        <span class="info-label">Hora:</span>
                        <div class="info-value">{{ \Carbon\Carbon::parse($emergencia->hora)->format('h:i A') }}</div>
                    </div>
                    <div class="col-md-6 info-block">
                        <span class="info-label">Motivo de la Emergencia:</span>
                        <textarea class="form-control" rows="3" readonly style="resize:none; background-color:#f8f9fa;">{{ $emergencia->motivo ?? '—' }}</textarea>
                    </div>

                    {{-- Signos Vitales --}}
                    <div class="col-12 mt-3"><div class="section-title">Signos Vitales</div></div>
                    <div class="col-md-4 info-block text-center">
                        <span class="vital-label">Presión Arterial</span>
                        <span class="vital-value">{{ $emergencia->pa ?? '—' }}</span>
                    </div>
                    <div class="col-md-4 info-block text-center">
                        <span class="vital-label">Frecuencia Cardíaca</span>
                        <span class="vital-value">{{ $emergencia->fc ?? '—' }}</span>
                    </div>
                    <div class="col-md-4 info-block text-center">
                        <span class="vital-label">Temperatura</span>
                        <span class="vital-value">{{ $emergencia->temp ? $emergencia->temp . ' °C' : '—' }}</span>
                    </div>
                </div>

                {{-- Historial --}}
                <div class="mt-4" id="historialTabla">
                    <div class="section-title">Historial de Emergencias Previas</div>
                    @if($historial->isEmpty())
                        <div class="alert alert-info text-center mt-2">
                            No hay emergencias previas registradas para este paciente.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered mt-2">
                                <thead class="table-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Signos Vitales</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($historial as $emergencia)
                                    <tr>
                                        <td>{{ $emergencia->fecha }}</td>
                                        <td>{{ $emergencia->hora }}</td>
                                        <td>
                                            PA: {{ $emergencia->pa }} <br>
                                            FC: {{ $emergencia->fc }} <br>
                                            Temp: {{ $emergencia->temp }}
                                        </td>
                                        <td>
                                            @php
                                                $emergenciaJson = [
                                                    'fecha' => $emergencia->fecha,
                                                    'hora' => $emergencia->hora,
                                                    'motivo' => $emergencia->motivo,
                                                    'pa' => $emergencia->pa,
                                                    'fc' => $emergencia->fc,
                                                    'temp' => $emergencia->temp,
                                                    'paciente' => [
                                                        'nombre' => $emergencia->paciente->nombre,
                                                        'apellido' => $emergencia->paciente->apellido,
                                                        'edad' => $emergencia->paciente->edad,
                                                        'tipo_sangre' => $emergencia->paciente->tipo_sangre,
                                                        'genero' => $emergencia->paciente->genero
                                                    ],
                                                    'archivo' => $emergencia->archivo ? [
                                                        'url' => $emergencia->archivo->url,
                                                        'nombre' => $emergencia->archivo->nombre
                                                    ] : null,
                                                    'foto' => $emergencia->foto_url ?? null
                                                ];
                                            @endphp

                                            <button class="verEmergenciaBtn btn btn-sm btn-primary"
                                                    data-emergencia='@json($emergenciaJson)'>
                                                Ver
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <div class="text-center pt-3">
                        <a href="{{ route('emergencias.index') }}" class="btn btn-success btn-sm px-4 shadow-sm">← Regresar</a>
                        <a href="{{ route('hospitalizaciones.create', ['emergencia_id' => $emergencia->id]) }}" class="btn btn-warning btn-sm px-4 shadow-sm">
                            Transferir a Hospitalización
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Foto --}}
        @if($mostrarFoto)
            <div class="modal fade" id="fotoModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content bg-transparent border-0 d-flex justify-content-center align-items-center" style="background:rgba(0,0,0,0.7);">
                        <div class="modal-body p-0" style="max-width:90vw; max-height:90vh;">
                            <img src="{{ asset('storage/' . $emergencia->foto) }}" alt="Foto del paciente"
                                 style="max-width:550px; max-height:550px; object-fit:contain; cursor:pointer;" id="imagenGrande">
                        </div>
                    </div>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const img = document.getElementById('imagenGrande');
                    if (img) {
                        img.addEventListener('click', () => {
                            const modalEl = document.getElementById('fotoModal');
                            const inst = bootstrap.Modal.getInstance(modalEl);
                            if (inst) inst.hide();
                        });
                    }
                });
            </script>
        @endif

        {{-- Modal Detalles Emergencia --}}
        {{-- Modal Detalles Emergencia (secciones y motivo al final) --}}
        <div class="modal fade" id="emergenciaModal" tabindex="-1" aria-labelledby="emergenciaModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content border-0 shadow rounded-3">

                    {{-- Header --}}
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="emergenciaModalLabel">Detalles de Emergencia</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    {{-- Body --}}
                    <div class="modal-body">
                        <div class="container-fluid">

                            {{-- Sección Paciente --}}
                            <div class="card mb-3 shadow-sm rounded-3">
                                <div class="card-header bg-light fw-bold">Paciente</div>
                                <div class="card-body row">
                                    <div class="col-md-4 mb-2"><strong>Nombre:</strong> <span id="modalPacienteNombre">—</span></div>
                                    <div class="col-md-4 mb-2"><strong>Apellido:</strong> <span id="modalPacienteApellido">—</span></div>
                                    <div class="col-md-4 mb-2"><strong>Edad:</strong> <span id="modalPacienteEdad">—</span></div>
                                    <div class="col-md-4 mb-2"><strong>Género:</strong> <span id="modalPacienteGenero">—</span></div>
                                    <div class="col-md-4 mb-2"><strong>Tipo de Sangre:</strong> <span id="modalPacienteSangre">—</span></div>
                                </div>
                            </div>

                            {{-- Sección Emergencia (Signos Vitales) --}}
                            <div class="card mb-3 shadow-sm rounded-3">
                                <div class="card-header bg-light fw-bold">Signos Vitales</div>
                                <div class="card-body row">
                                    <div class="col-md-4 mb-2"><strong>PA:</strong> <span id="modalPA">—</span></div>
                                    <div class="col-md-4 mb-2"><strong>FC:</strong> <span id="modalFC">—</span></div>
                                    <div class="col-md-4 mb-2"><strong>Temp:</strong> <span id="modalTemp">—</span></div>
                                    <div class="col-md-6 mb-2"><strong>Fecha:</strong> <span id="modalFecha">—</span></div>
                                    <div class="col-md-6 mb-2"><strong>Hora:</strong> <span id="modalHora">—</span></div>
                                </div>
                            </div>

                            {{-- Motivo --}}
                            <div class="card mb-3 shadow-sm rounded-3">
                                <div class="card-header bg-light fw-bold">Motivo</div>
                                <div class="card-body">
                                    <p id="modalMotivo">—</p>
                                </div>
                            </div>

                            {{-- Archivo / Foto --}}
                            <div class="card mb-3 shadow-sm rounded-3" id="modalArchivoCard" style="display:none;">
                                <div class="card-body d-flex flex-column align-items-center">
                                    <div id="modalArchivoLinkContainer" style="display:none;" class="mb-3">
                                        <a id="modalArchivoLink" href="#" target="_blank" class="btn btn-outline-secondary btn-sm">Abrir archivo</a>
                                    </div>
                                    <div id="modalFotoContainer" style="display:none;">
                                        <img id="modalFoto" src="#" alt="Foto" class="img-fluid rounded shadow" style="max-width:400px; max-height:400px; object-fit:cover;">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>

                </div>
            </div>
        </div>



    @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const buttons = document.querySelectorAll('.verEmergenciaBtn');
                    const modalElement = document.getElementById('emergenciaModal');
                    const modalBootstrap = new bootstrap.Modal(modalElement);

                    function mostrarEmergencia(emergencia) {
                        // Datos del paciente
                        document.getElementById('modalPacienteNombre').textContent = emergencia.paciente.nombre || '—';
                        document.getElementById('modalPacienteApellido').textContent = emergencia.paciente.apellido || '—';
                        document.getElementById('modalPacienteEdad').textContent = emergencia.paciente.edad || '—';
                        document.getElementById('modalPacienteSangre').textContent = emergencia.paciente.tipo_sangre || '—';
                        document.getElementById('modalPacienteGenero').textContent = emergencia.paciente.genero || '—';

                        // Datos de la emergencia
                        document.getElementById('modalFecha').textContent = emergencia.fecha || '—';
                        document.getElementById('modalHora').textContent = emergencia.hora || '—';
                        document.getElementById('modalMotivo').textContent = emergencia.motivo || '—';
                        document.getElementById('modalPA').textContent = emergencia.pa || '—';
                        document.getElementById('modalFC').textContent = emergencia.fc || '—';
                        document.getElementById('modalTemp').textContent = emergencia.temp || '—';

                        // Archivo / Foto
                        const archivoCard = document.getElementById('modalArchivoCard');
                        const archivoLinkContainer = document.getElementById('modalArchivoLinkContainer');
                        const archivoLink = document.getElementById('modalArchivoLink');
                        const fotoContainer = document.getElementById('modalFotoContainer');
                        const foto = document.getElementById('modalFoto');

                        if (emergencia.archivo) {
                            archivoCard.style.display = 'block';
                            archivoLinkContainer.style.display = 'block';
                            archivoLink.href = emergencia.archivo.url || '#';
                            archivoLink.textContent = emergencia.archivo.nombre || 'Abrir archivo';
                        } else {
                            archivoLinkContainer.style.display = 'none';
                        }

                        if (emergencia.foto) {
                            archivoCard.style.display = 'block';
                            fotoContainer.style.display = 'block';
                            foto.src = emergencia.foto || '#';
                        } else {
                            fotoContainer.style.display = 'none';
                        }

                        if (!emergencia.archivo && !emergencia.foto) {
                            archivoCard.style.display = 'none';
                        }

                        // Abrir modal
                        modalBootstrap.show();
                    }

                    // Asignar evento click a todos los botones
                    buttons.forEach(button => {
                        button.addEventListener('click', function () {
                            const emergencia = JSON.parse(this.dataset.emergencia);
                            mostrarEmergencia(emergencia);
                        });
                    });
                });
            </script>
    @endpush

@endsection
