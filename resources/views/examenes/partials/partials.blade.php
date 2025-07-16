{{-- resources/views/ordenes/detalle.blade.php --}}
<div style="font-family: Arial, sans-serif; padding: 20px;">
    <h4>Orden de Examen #{{ $orden->id }}</h4>

    <p><strong>Paciente:</strong> {{ $orden->paciente->nombre ?? 'N/A' }} {{ $orden->paciente->apellidos ?? '' }}</p>
    <p><strong>Consulta ID:</strong> {{ $orden->consulta->id ?? 'N/A' }}</p>
    <p><strong>Fecha de creación:</strong> {{ $orden->created_at->format('d/m/Y H:i') }}</p>

    <hr>

    <p><strong>Nombre del examen:</strong> {{ $orden->nombre ?? 'No especificado' }}</p>

    {{-- Aquí puedes agregar más campos de tu examen si los tienes --}}
</div>

