@extends('layouts.app')

@section('content')
<style>
    /* (Tu CSS actual, más este para la lista desplegable) */
    #medicamento-list {
        position: absolute;
        background: white;
        border: 1px solid #ccc;
        max-height: 150px;
        overflow-y: auto;
        width: 100%;
        z-index: 1000;
        display: none;
        cursor: pointer;
    }
    #medicamento-list div {
        padding: 8px;
        border-bottom: 1px solid #eee;
    }
    #medicamento-list div:hover {
        background-color: #007BFF;
        color: white;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }
    th, td {
        border: 1px solid #ccc;
        padding: 0.5rem;
        text-align: left;
    }
    .btn-remove {
        color: red;
        cursor: pointer;
    }
</style>

<div class="custom-card">
    <h2 class="text-center text-xl font-bold">CLINITEK</h2>

    <!-- Info del médico y paciente (igual que antes) -->
    <div class="text-center mb-2">
        <strong>Dr. {{ $consulta->medico->nombre ?? 'Nombre no disponible' }}</strong><br>
        <span>Especialista en {{ $consulta->medico->especialidad ?? 'Especialidad no disponible' }}</span><br>
        <hr style="border: none; border-top: 3px solid #0056b3; margin: 0 auto 1rem; width: 100%;">
    </div>

    <p><strong>Paciente:</strong> {{ $consulta->paciente->nombre ?? 'Sin asignar' }}</p>
    <p><strong>Fecha:</strong> {{ $consulta->fecha ?? 'Sin fecha' }}</p>

    <form action="{{ route('recetas.store', ['paciente' => $consulta->paciente->id]) }}" method="POST" novalidate>
        @csrf
        <input type="hidden" name="consulta_id" value="{{ $consulta->id }}">

        <!-- Nuevo campo para medicamento con autocompletado -->
        <div style="position: relative; margin-bottom: 1rem;">
            <label for="medicamento" style="font-weight: 600;">Medicamento:</label>
            <input type="text" id="medicamento" placeholder="Escribe el nombre del medicamento" autocomplete="off">
            <div id="medicamento-list"></div>
        </div>

        <!-- Indicaciones -->
        <div style="margin-bottom: 1rem;">
            <label for="indicacion" style="font-weight: 600;">Indicaciones:</label>
            <select id="indicacion" style="width: 100%; padding: 0.5rem; border-radius: 4px; border: 1px solid #ccc;">
                <option value="8 horas">Cada 8 horas</option>
                <option value="12 horas">Cada 12 horas</option>
                <option value="24 horas">Cada 24 horas</option>
                <option value="Una vez al día">Una vez al día</option>
                <option value="Antes de dormir">Antes de dormir</option>
            </select>
        </div>

        <!-- Botón para agregar medicamento a la tabla -->
        <button type="button" id="agregar-medicamento" 
            style="background-color: #28a745; color: white; padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer; margin-bottom: 1rem;">
            Agregar Medicamento
        </button>

        <!-- Tabla donde se mostrarán los medicamentos agregados -->
        <table id="tabla-medicamentos" style="display: none;">
            <thead>
                <tr>
                    <th>Medicamento</th>
                    <th>Indicaciones</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <!-- Filas se agregarán aquí -->
            </tbody>
        </table>

        <!-- Detalles de prescripción -->
        <div style="margin-top: 3rem;">
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
    // Conteo de caracteres detalles
    function updateCount() {
        const detalles = document.getElementById('detalles');
        const count = document.getElementById('charCount');
        count.textContent = detalles.value.length;
    }
    document.addEventListener('DOMContentLoaded', updateCount);

    // Variables DOM
    const medicamentoInput = document.getElementById('medicamento');
    const medicamentoList = document.getElementById('medicamento-list');
    const indicacionSelect = document.getElementById('indicacion');
    const agregarBtn = document.getElementById('agregar-medicamento');
    const tablaMedicamentos = document.getElementById('tabla-medicamentos').getElementsByTagName('tbody')[0];

    // Para guardar medicamentos agregados y evitar duplicados
    const medicamentosAgregados = new Set();

    // Función para mostrar sugerencias desde backend
    medicamentoInput.addEventListener('input', async () => {
        const texto = medicamentoInput.value.trim();
        if (texto.length === 0) {
            medicamentoList.style.display = 'none';
            return;
        }
        const res = await fetch(`/medicamentos/search?q=${encodeURIComponent(texto)}`);
        const data = await res.json();

        medicamentoList.innerHTML = '';
        if (data.length === 0) {
            medicamentoList.style.display = 'none';
            return;
        }

        data.forEach(med => {
            const div = document.createElement('div');
            div.textContent = med;
            div.addEventListener('click', () => {
                medicamentoInput.value = med;
                medicamentoList.style.display = 'none';
            });
            medicamentoList.appendChild(div);
        });
        medicamentoList.style.display = 'block';
    });

    // Ocultar lista si clic afuera
    document.addEventListener('click', (e) => {
        if (!medicamentoInput.contains(e.target) && !medicamentoList.contains(e.target)) {
            medicamentoList.style.display = 'none';
        }
    });

    // Agregar medicamento a tabla
    agregarBtn.addEventListener('click', () => {
        const med = medicamentoInput.value.trim();
        const indicacion = indicacionSelect.value;

        if (!med) {
            alert('Por favor, escribe o selecciona un medicamento.');
            return;
        }
        if (medicamentosAgregados.has(med.toLowerCase())) {
            alert('El medicamento ya está agregado.');
            return;
        }

        // Mostrar tabla si está oculta
        if (document.getElementById('tabla-medicamentos').style.display === 'none') {
            document.getElementById('tabla-medicamentos').style.display = 'table';
        }

        // Crear fila
        const tr = document.createElement('tr');

        const tdMed = document.createElement('td');
        tdMed.textContent = med;
        tr.appendChild(tdMed);

        const tdInd = document.createElement('td');
        tdInd.textContent = indicacion;
        tr.appendChild(tdInd);

        const tdAccion = document.createElement('td');
        const btnRemove = document.createElement('span');
        btnRemove.textContent = 'Eliminar';
        btnRemove.className = 'btn-remove';
        btnRemove.addEventListener('click', () => {
            tablaMedicamentos.removeChild(tr);
            medicamentosAgregados.delete(med.toLowerCase());
            if(tablaMedicamentos.rows.length === 0){
                document.getElementById('tabla-medicamentos').style.display = 'none';
            }
        });
        tdAccion.appendChild(btnRemove);
        tr.appendChild(tdAccion);

        tablaMedicamentos.appendChild(tr);

        // Guardar medicamento agregado para evitar duplicados
        medicamentosAgregados.add(med.toLowerCase());

        // Limpiar input y select
        medicamentoInput.value = '';
        indicacionSelect.selectedIndex = 0;
        medicamentoList.style.display = 'none';
    });
</script>
@endsection
