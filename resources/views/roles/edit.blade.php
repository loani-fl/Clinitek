@extends('layouts.app')

@section('content')

    ```
    <style>
        .custom-card {
            max-width: 1100px;
            background-color: rgba(255, 255, 255, 0.95);
            border: 1px solid #91cfff;
            border-radius: 0.5rem;
            position: relative;
            overflow: hidden;
            margin: 2rem auto;
            padding: 2rem;
            box-shadow: 0 0 15px rgba(0,123,255,0.25);
            z-index: 1;
        }

        .custom-card::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 900px;
            height: 900px;
            background-image: url('/images/logo2.jpg');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.12;
            transform: translate(-50%, -50%);
            pointer-events: none;
            z-index: 0;
        }

        .section-title {
            font-weight: bold;
            padding-bottom: 6px;
            border-bottom: 3px solid #007BFF;
            margin-bottom: 15px;
            color: #000;
        }
    </style>

    <div class="custom-card">

        <h2 class="text-center fw-bold section-title mb-4">Editar rol</h2>

        {{-- Mensajes de éxito --}}
        @if(session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif

        <form action="{{ route('roles.update', $role->id) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            {{-- Nombre del rol --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nombre del rol:</label>
                    <input type="text"
                           name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           placeholder="Ej: administrador"
                           value="{{ old('name', $role->name) }}"
                           maxlength="50"
                           required
                           oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g,'')">
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            @php
                $allPermissions = collect($permissions ?? $permisos ?? []);
                $rolePermissions = $role->permissions->pluck('name')->toArray();
                $controllerSections = [
                    'Usuarios' => $allPermissions->filter(fn($p) => str_starts_with($p->name, 'usuarios.')),
                    'Pacientes' => $allPermissions->filter(fn($p) => str_starts_with($p->name, 'pacientes.')),
                    'Médicos' => $allPermissions->filter(fn($p) => str_starts_with($p->name, 'medicos.')),
                    'Empleados' => $allPermissions->filter(fn($p) => str_starts_with($p->name, 'empleado.') || str_starts_with($p->name, 'empleados.')),
                    'Consultas' => $allPermissions->filter(fn($p) => str_starts_with($p->name, 'consultas.')),
                    'Control Prenatal' => $allPermissions->filter(fn($p) => str_starts_with($p->name, 'controlPrenatal.')),
                    'Recetas' => $allPermissions->filter(fn($p) => str_starts_with($p->name, 'recetas.')),
                    'Rayos X' => $allPermissions->filter(fn($p) => str_starts_with($p->name, 'rayosX.')),
                    'Ultrasonidos' => $allPermissions->filter(fn($p) => str_starts_with($p->name, 'ultrasonidos.')),
                    'Inventario' => $allPermissions->filter(fn($p) => str_starts_with($p->name, 'inventario.')),
                    'Farmacias' => $allPermissions->filter(fn($p) => str_starts_with($p->name, 'farmacias.')),
                    'Hospitalización' => $allPermissions->filter(fn($p) => str_starts_with($p->name, 'hospitalizacion.')),
                    'Diagnósticos' => $allPermissions->filter(fn($p) => str_starts_with($p->name, 'diagnosticos.')),
                    'Emergencias' => $allPermissions->filter(fn($p) => str_starts_with($p->name, 'emergencias.')),
                    'Puestos' => $allPermissions->filter(fn($p) => str_starts_with($p->name, 'puestos.')),
                    'Exámenes' => $allPermissions->filter(fn($p) => str_starts_with($p->name, 'examenes.')),
                    'Sesiones' => $allPermissions->filter(fn($p) => str_starts_with($p->name, 'sesiones.')),
                ];
                $accionesBonitas = [
                    'Index' => 'Listar',
                    'Show' => 'Ver',
                    'Create' => 'Crear',
                    'Edit' => 'Editar',
                    'Delete' => 'Eliminar',
                ];
            @endphp

            <div class="row">
                <div class="col-md-12">
                    <h5 class="section-title">Permisos del rol</h5>
                    <div class="row">
                        @foreach($controllerSections as $sectionName => $permList)
                            @if($permList && $permList->count() > 0)
                                <div class="col-md-6 mb-4">
                                    {{-- Checkbox de sección --}}
                                    <div class="d-flex align-items-center mb-2">
                                        @php
                                            $slug = \Illuminate\Support\Str::slug($sectionName);
                                            $sectionChecked = $permList->pluck('name')->intersect($rolePermissions)->count() > 0;
                                        @endphp
                                        <input type="checkbox"
                                               class="form-check-input section-checkbox me-2"
                                               id="section_{{ $slug }}"
                                            {{ $sectionChecked ? 'checked' : '' }}>
                                        <label class="fw-bold text-primary mb-0" for="section_{{ $slug }}">
                                            {{ $sectionName }}
                                        </label>
                                    </div>

                                    {{-- Permisos individuales --}}
                                    @foreach($permList as $perm)
                                        @php
                                            $partes = explode('.', $perm->name);
                                            $modulo = ucfirst($partes[0] ?? '');
                                            $accionOriginal = ucfirst($partes[1] ?? '');
                                            $accion = $accionesBonitas[$accionOriginal] ?? $accionOriginal;
                                            $checked = in_array($perm->name, $rolePermissions);
                                        @endphp

                                        {{-- Saltar INDEX (Listar) --}}
                                        @if(strtolower($partes[1]) === 'index')
                                            <input type="checkbox"
                                                   name="permissions[]"
                                                   value="{{ $perm->name }}"
                                                   id="perm_index_{{ $slug }}"
                                                   class="index-permission section-permission-{{ $slug }}"
                                                   hidden
                                                {{ $checked ? 'checked' : '' }}>
                                            @continue
                                        @endif

                                        {{-- Permisos visibles --}}
                                        <div class="form-check mb-1">
                                            <input class="form-check-input section-permission-{{ $slug }}"
                                                   type="checkbox"
                                                   name="permissions[]"
                                                   value="{{ $perm->name }}"
                                                   id="perm_{{ \Illuminate\Support\Str::slug($perm->name) }}"
                                                {{ $checked ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm_{{ \Illuminate\Support\Str::slug($perm->name) }}">
                                                {{ $accion }} {{ $modulo }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Error de permisos --}}
            @error('permissions')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror

            @php
                // Variables originales considerando old() por validación fallida
                $originalName = old('name', $role->name);
                $originalPermissions = old('permissions', $role->permissions->pluck('name')->toArray());
            @endphp

            {{-- Botones --}}
            <div class="d-flex justify-content-center gap-4 mt-3">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save"></i> Actualizar rol
                </button>

                <button type="button" id="btnRestablecer" class="btn btn-warning px-4"> <i class="bi bi-arrow-counterclockwise"></i> Restablecer </button>

                <a href="{{ route('roles.index') }}" class="btn btn-success px-4">
                    <i class="bi bi-arrow-left"></i> Regresar
                </a>
            </div>

        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                // 1️⃣ Configuración inicial de checkboxes según rol
                document.querySelectorAll('.section-checkbox').forEach(section => {
                    const slug = section.id.replace('section_', '');
                    const permisos = document.querySelectorAll('.section-permission-' + slug);
                    const indexCheckbox = document.querySelector('.index-permission.section-permission-' + slug);

                    if(section.checked){
                        permisos.forEach(cb => cb.disabled = false);
                    } else {
                        permisos.forEach(cb => cb.disabled = true);
                        permisos.forEach(cb => cb.checked = false);
                        if(indexCheckbox) indexCheckbox.checked = false;
                    }

                    // Manejar cambios dinámicos
                    section.addEventListener('change', function () {
                        if (this.checked) {
                            if (indexCheckbox) indexCheckbox.checked = true;
                            permisos.forEach(cb => cb.disabled = false);
                        } else {
                            permisos.forEach(cb => { cb.disabled = true; cb.checked = false; });
                            if (indexCheckbox) indexCheckbox.checked = false;
                        }
                    });
                });



            });
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {

                // --- Guardamos los valores originales al cargar la página ---
                const originalName = @json($originalName);
                const originalPermissions = @json($originalPermissions);

                // --- Función para aplicar los permisos originales ---
                function aplicarPermisosOriginales() {
                    // Restaurar nombre
                    const nameInput = document.querySelector('input[name="name"]');
                    nameInput.value = originalName;

                    // Restaurar checkboxes
                    document.querySelectorAll('.section-checkbox').forEach(section => {
                        const slug = section.id.replace('section_', '');
                        const permisos = document.querySelectorAll('.section-permission-' + slug);
                        const indexCheckbox = document.querySelector('.index-permission.section-permission-' + slug);

                        // Determinar si la sección debe estar marcada
                        const sectionChecked = Array.from(permisos).some(cb => originalPermissions.includes(cb.value));
                        section.checked = sectionChecked;

                        // INDEX oculto
                        if(indexCheckbox) indexCheckbox.checked = sectionChecked;

                        // Marcar permisos individuales según originalPermissions
                        permisos.forEach(cb => {
                            cb.checked = originalPermissions.includes(cb.value);
                            cb.disabled = !section.checked;
                        });
                    });
                }

                // --- Botón Restablecer ---
                document.getElementById('btnRestablecer').addEventListener('click', function () {
                    aplicarPermisosOriginales();
                });

                // Aplicar al cargar por si hay old() después de error de validación
                aplicarPermisosOriginales();

            });
        </script>
    @endpush
    ```

@endsection

