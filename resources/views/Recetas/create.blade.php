@extends('layouts.app')

@section('content')
<style>
    #medicamento-list {
        position: absolute;
        background: white;
        border: 1px solid #ccc;
        max-height: 150px;
        overflow-y: auto;
        width: 35%;
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
    table td {
    white-space: normal !important;
    word-wrap: break-word;
    word-break: break-word;
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
    .signature-line {
        margin-top: 2rem;
        border-top: 1px solid #000;
        width: 200px;
        text-align: center;
        font-style: italic;
        color: #555;
        padding-top: 5px;
    }

    .custom-card {
        position: relative;
        background-color: white;
        padding: 2.5rem;
        border-radius: 12px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        border: 1px solid #ccc;
        overflow: hidden;
        max-width: 900px;
        margin: 2.5rem auto;
    }
    .custom-card::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        width: 900px;
        height: 900px;
        background-image: url('{{ asset("images/barra.png") }}');
        background-repeat: no-repeat;
        background-size: contain;
        background-position: center;
        opacity: 0.1;
        transform: translate(-50%, -50%);
        pointer-events: none;
        z-index: 0;
    }
    .custom-card > * {
        position: relative;
        z-index: 1;
    }
    .error-message {
        color: red;
        font-size: 0.9rem;
        margin-top: 0.25rem;
    }
</style>

<div class="custom-card">
    <h2 class="text-center text-xl font-bold">CLINITEK</h2>

    <div class="text-center mb-2">
    <strong>Dr. {{ $consulta->medico->nombre ?? 'Nombre' }} {{ $consulta->medico->apellidos ?? 'no disponible' }}</strong><br>
    <span>Especialista en {{ $consulta->medico->especialidad ?? 'Especialidad no disponible' }}</span><br>
    <hr style="border: none; border-top: 3px solid #0056b3; margin: 0 auto 1rem; width: 100%;">
</div>


    @php
        use Carbon\Carbon;
        $edad = $consulta->paciente->fecha_nacimiento
            ? Carbon::parse($consulta->paciente->fecha_nacimiento)->age
            : 'Edad no disponible';
    @endphp

    <div style="display: flex; align-items: center; gap: 8rem; margin-bottom: 1rem;">
        <p><strong>Paciente:</strong> {{ $consulta->paciente->nombre ?? '' }} {{ $consulta->paciente->apellidos ?? '' }}</p>
        <p><strong>Edad:</strong> {{ $edad }}</p>
        <p><strong>Fecha:</strong> {{ $consulta->fecha ?? 'Sin fecha' }}</p>
    </div>


    <form action="{{ route('recetas.store', ['consulta' => $consulta->id]) }}" method="POST">


    @csrf
    <input type="hidden" name="consulta_id" value="{{ $consulta->id }}">


        <!-- Medicamento con autocompletado -->
        <div style="position: relative; margin-bottom: 1rem;">
    <label for="medicamento" style="font-weight: 600;">Medicamento:</label>
    <input
        type="text"
        id="medicamento"
        name="medicamentos[0][nombre]"
        maxlength="55"
        placeholder="Escribe el nombre del medicamento"
        autocomplete="off"
        style="width: 35%; padding: 0.5rem; border-radius: 4px; border: 1px solid #ccc;"
        value="{{ old('medicamentos.0.nombre') }}">
    <div id="medicamento-list"></div>
    <div id="error-medicamento" class="error-message"></div>
@error('medicamentos.0.nombre')
    <small class="text-danger">{{ $message }}</small>
@enderror

</div>

        <!-- Indicaciones -->
        <div style="margin-bottom: 1rem;">
            <label for="indicacion" style="font-weight: 600;">Indicaciones:</label>
            <select id="indicacion" style="width: 18%; padding: 0.5rem; border-radius: 4px; border: 1px solid #ccc;">
                <option value="Cada 8 horas">Cada 8 horas</option>
                <option value="Cada 12 horas">Cada 12 horas</option>
                <option value="Cada 24 horas">Cada 24 horas</option>
                <option value="Una vez al día">Cada 6 horas</option>
                <option value="Antes de dormir">Antes de dormir</option>
            </select>
            @error('medicamentos.0.indicacion')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Dosis -->
        <div style="margin-bottom: 1rem;">
    <label for="dosis" style="font-weight: 600;">Dosis:</label>
    <input
        type="text"
        id="dosis"
        maxlength="25"
        placeholder="Ejemplo: 500 mg"
        style="width: 20%; padding: 0.5rem; border-radius: 4px; border: 1px solid #ccc;">
        <div id="error-dosis" class="error-message"></div>

    @error('medicamentos.0.dosis')
        <div class="error-message">{{ $message }}</div>
    @enderror
</div>


        <!-- Detalles de prescripción (individual) -->
        <div id="detalles-container" style="margin-bottom: 1rem; display: none;">
            <label for="detalles" style="display: block; font-weight: 600; margin-bottom: 0.25rem;">
                Detalles de prescripción:
            </label>
            <textarea
                id="detalles"
                placeholder="Escribe los detalles aquí"
                maxlength="500"
                style="width: 100%; height: 120px; padding: 0.5rem; border: 1px solid #ccc; border-radius: 6px;"></textarea>
            <div style="text-align: right; font-size: 0.8rem; color: #555;">
                <span id="charCount">0</span>/500 caracteres
            </div>
            <div id="error-detalles" class="error-message"></div>

        </div>

        <!-- Botón para agregar medicamento -->
        <button type="button" id="agregar-medicamento"
            style="background-color: #28a745; color: white; padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer; margin-bottom: 1rem;">
            Agregar Medicamento
        </button>

        <!-- Tabla medicamentos -->
        <table id="tabla-medicamentos" style="display: none; width: 100%; margin-bottom: 2rem;">
            <thead>
                <tr>
                    <th>Medicamento</th>
                    <th>Indicaciones</th>
                    <th>Dosis</th>
                    <th>Detalles de prescripción</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <!-- Campo oculto para enviar medicamentos como array -->
        <div id="medicamentos-hidden"></div>
        @error('medicamentos')
            <div class="error-message">{{ $message }}</div>
        @enderror

        <!-- Firma -->
        <div class="signature-line">Firma del Médico</div>

        <!-- Botones -->
        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <a href="{{ route('consultas.show', $consulta->id) }}"
                style="background-color: #808080; color: black; padding: 10px 16px; border-radius: 6px; text-decoration: none;">
                Imprimir
            </a>

            <button type="submit"
                style="background-color: #007BFF; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer;">
                Guardar
            </button>
        </div>

    </form>
</div>

<script>
    function updateCount() {
        const detalles = document.getElementById('detalles');
        const count = document.getElementById('charCount');
        count.textContent = detalles.value.length;
    }
    document.addEventListener('DOMContentLoaded', () => {
    const medicamentoInput = document.getElementById('medicamento');
    const medicamentoList = document.getElementById('medicamento-list');
    const indicacionSelect = document.getElementById('indicacion');
    const dosisInput = document.getElementById('dosis');
    const detallesContainer = document.getElementById('detalles-container');
    const detallesTextarea = document.getElementById('detalles');
    const agregarBtn = document.getElementById('agregar-medicamento');
    const tablaMedicamentos = document.getElementById('tabla-medicamentos').querySelector('tbody');
    const hiddenContainer = document.getElementById('medicamentos-hidden');
    const medicamentosAgregados = new Set();

    function updateCount() {
        const detalles = detallesTextarea;
        const count = document.getElementById('charCount');
        count.textContent = detalles.value.length;
    }
    updateCount();
    detallesTextarea.addEventListener('input', updateCount);

    // Función que busca y muestra sugerencias
    async function fetchSugerencias(texto) {
        if (texto.length === 0) {
            medicamentoList.style.display = 'none';
            return;
        }
        try {
            const res = await fetch(`/medicamentos/search?q=${encodeURIComponent(texto)}`);
            if (!res.ok) {
                medicamentoList.style.display = 'none';
                return;
            }
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
                    detallesContainer.style.display = 'block';
                    updateCount();
                    dosisInput.value = '';
                    medicamentoInput.focus();
                });
                medicamentoList.appendChild(div);
            });
            medicamentoList.style.display = 'block';
        } catch (error) {
            medicamentoList.style.display = 'none';
            console.error('Error al obtener sugerencias:', error);
        }
    }

    medicamentoInput.addEventListener('input', () => {
        const texto = medicamentoInput.value.trim();

        // Mostrar detalles si hay texto
        if (texto.length > 0) {
            detallesContainer.style.display = 'block';
        } else {
            detallesContainer.style.display = 'none';
            detallesTextarea.value = '';
            updateCount();
            medicamentoList.style.display = 'none';
            return;
        }

        fetchSugerencias(texto);
    });

    medicamentoInput.addEventListener('focus', () => {
        const texto = medicamentoInput.value.trim();
        fetchSugerencias(texto);
    });

    document.addEventListener('click', (e) => {
        if (!medicamentoInput.contains(e.target) && !medicamentoList.contains(e.target)) {
            medicamentoList.style.display = 'none';
        }
    });

    agregarBtn.addEventListener('click', () => {
        const med = medicamentoInput.value.trim();
        const indicacion = indicacionSelect.value;
        const dosis = dosisInput.value.trim();
        const detalles = detallesTextarea.value.trim();

            // Limpiar errores anteriores
        document.getElementById('error-medicamento').textContent = '';
        document.getElementById('error-dosis').textContent = '';
        document.getElementById('error-detalles').textContent = '';

        let error = false;

        if (!med) {
            document.getElementById('error-medicamento').textContent = 'Por favor, ingresa un medicamento.';
            error = true;
        } else if (medicamentosAgregados.has(med.toLowerCase())) {
            document.getElementById('error-medicamento').textContent = 'Este medicamento ya fue agregado.';
            error = true;
        }

        if (!dosis) {
            document.getElementById('error-dosis').textContent = 'Por favor, ingresa la dosis.';
            error = true;
        }

        if (!detalles) {
            document.getElementById('error-detalles').textContent = 'Por favor, ingresa los detalles de prescripción.';
            error = true;
        }

        if (error) return;


        if (document.getElementById('tabla-medicamentos').style.display === 'none') {
            document.getElementById('tabla-medicamentos').style.display = 'table';
        }

        const tr = document.createElement('tr');

        const tdMed = document.createElement('td');
        tdMed.textContent = med;
        tdMed.style.wordBreak = 'break-word'; // Para evitar que distorsione la tabla
        tr.appendChild(tdMed);

        const tdInd = document.createElement('td');
        tdInd.textContent = indicacion;
        tdInd.style.wordBreak = 'break-word';
        tr.appendChild(tdInd);

        const tdDosis = document.createElement('td');
        tdDosis.textContent = dosis;
        tdDosis.style.wordBreak = 'break-word';
        tr.appendChild(tdDosis);

        const tdDetalles = document.createElement('td');
        tdDetalles.textContent = detalles;
        tdDetalles.style.wordBreak = 'break-word';
        tr.appendChild(tdDetalles);

        const tdAccion = document.createElement('td');
        const btnRemove = document.createElement('span');
        btnRemove.textContent = 'Borrar';
        btnRemove.className = 'btn-remove';
        btnRemove.addEventListener('click', () => {
            tablaMedicamentos.removeChild(tr);
            medicamentosAgregados.delete(med.toLowerCase());

            // Eliminar inputs ocultos asociados
            const hiddenInputs = hiddenContainer.querySelectorAll(`input[data-med="${med.toLowerCase()}"]`);
            hiddenInputs.forEach(input => hiddenContainer.removeChild(input));

            if (tablaMedicamentos.rows.length === 0) {
                document.getElementById('tabla-medicamentos').style.display = 'none';
            }
        });
        tdAccion.appendChild(btnRemove);
        tr.appendChild(tdAccion);

        tablaMedicamentos.appendChild(tr);

        const index = medicamentosAgregados.size;

        // Crear inputs ocultos para enviar el formulario

        const hiddenNombre = document.createElement('input');
        hiddenNombre.type = 'hidden';
        hiddenNombre.name = `medicamentos[${index}][nombre]`;
        hiddenNombre.value = med;
        hiddenNombre.dataset.med = med.toLowerCase();
        hiddenContainer.appendChild(hiddenNombre);

        const hiddenIndicacion = document.createElement('input');
        hiddenIndicacion.type = 'hidden';
        hiddenIndicacion.name = `medicamentos[${index}][indicacion]`;
        hiddenIndicacion.value = indicacion;
        hiddenContainer.appendChild(hiddenIndicacion);

        const hiddenDosis = document.createElement('input');
        hiddenDosis.type = 'hidden';
        hiddenDosis.name = `medicamentos[${index}][dosis]`;
        hiddenDosis.value = dosis;
        hiddenContainer.appendChild(hiddenDosis);

        const hiddenDetalles = document.createElement('input');
        hiddenDetalles.type = 'hidden';
        hiddenDetalles.name = `medicamentos[${index}][detalles]`;
        hiddenDetalles.value = detalles;
        hiddenContainer.appendChild(hiddenDetalles);

        medicamentosAgregados.add(med.toLowerCase());

        // Limpiar campos
        medicamentoInput.value = '';
        indicacionSelect.selectedIndex = 0;
        dosisInput.value = '';
        detallesTextarea.value = '';
        detallesContainer.style.display = 'none';

        // Dar foco para seguir escribiendo y mostrar sugerencias si empieza a escribir
        medicamentoInput.focus();
        medicamentoList.style.display = 'none'; // reset lista hasta que escriba algo
    });
});

</script>


@endsection
