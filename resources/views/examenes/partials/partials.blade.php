{{-- resources/views/ordenes/detalle.blade.php --}}
<div style="font-family: Arial, sans-serif; padding: 20px;">
    <h4>Orden de Examen #{{ $orden->id }}</h4>

    <p><strong>Paciente:</strong> {{ $orden->paciente->nombre ?? 'N/A' }} {{ $orden->paciente->apellidos ?? '' }}</p>
    <p><strong>Consulta ID:</strong> {{ $orden->consulta->id ?? 'N/A' }}</p>
    <p><strong>Fecha de creación:</strong> {{ $orden->created_at->format('d/m/Y H:i') }}</p>

    <hr>

    @php
        $categorias = [
            'hematologia' => 'Hematología',
            'bioquimico' => 'Bioquímica',
            'perfil_anemia' => 'Perfil de Anemia',
            'perfil_diabetes' => 'Perfil de Diabetes',
            'marcador_tumoral' => 'Marcadores Tumorales',
            'hormona' => 'Hormonas',
            'infeccioso' => 'Infecciosos',
            'orina_fluido' => 'Orina y Fluidos',
            'inmunologia_autoinmunidad' => 'Inmunología y Autoinmunidad'
        ];
    @endphp

    @foreach ($categorias as $clave => $titulo)
        @if ($orden->$clave)
            <h5>{{ $titulo }}:</h5>
            <ul>
                @foreach ($orden->$clave->toArray() as $campo => $valor)
                    @if ($valor)
                        <li>{{ ucwords(str_replace('_', ' ', $campo)) }}</li>
                    @endif
                @endforeach
            </ul>
        @endif
    @endforeach


    {{-- Aquí puedes agregar más campos de tu examen si los tienes --}}
</div>

