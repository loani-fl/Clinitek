@extends('layouts.app')

@section('content')

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

        <h2 class="text-center fw-bold section-title mb-4">Crear Nuevo Rol</h2>

        {{-- Mensajes de éxito --}}
        @if(session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif



        <form action="{{ route('roles.store') }}" method="POST" novalidate>
            @csrf

            {{-- Nombre del rol --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nombre del Rol:</label>
                    <input type="text"
                           name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           placeholder="Ej: administrador"
                           value="{{ old('name') }}"
                           maxlength="50"
                           required
                           oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g,'')">
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Preparar permisos --}}
            @php
                $allPermissions = collect($permissions ?? $permisos ?? []);
                $controllerSections = [
                    'Usuarios' => $allPermissions->filter(fn($p) => str_starts_with($p->name, 'usuarios.')),
                    'Pacientes' => $allPermissions->filter(fn($p) => str_starts_with($p->name, 'pacientes.')),
                    'Médicos' => $allPermissions->filter(fn($p) => str_starts_with($p->name, 'medicos.')),
                 'Empleados' => $allPermissions->filter(function($p) {
    return str_starts_with($p->name, 'empleado.') || str_starts_with($p->name, 'empleados.');
}),

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
                    'Dashboard' => $allPermissions->filter(fn($p) => str_starts_with($p->name, 'dashboard.')),
                ];
                $accionesBonitas = [
                    'Index' => 'Listar',
                    'Show' => 'Ver',
                    'Create' => 'Crear',
                    'Edit' => 'Editar',
                    'Delete' => 'Eliminar',
                ];
            @endphp

            {{-- Checkbox de seleccionar todos --}}
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="selectAllPermisos">
                        <label class="form-check-label fw-bold" for="selectAllPermisos">Seleccionar todos los permisos</label>
                    </div>
                </div>
            </div>

            {{-- Sección de permisos --}}
            {{-- Sección de permisos --}}
            <div class="row">
                <div class="col-md-12">
                    <h5 class="section-title">Permisos del Rol</h5>
                    <div class="row">
                        @foreach($controllerSections as $sectionName => $permList)
                            @if($permList && $permList->count() > 0)
                                <div class="col-md-6 mb-4">
                                    {{-- Checkbox de sección --}}
                                    <div class="d-flex align-items-center mb-2">
                                        <input type="checkbox"
                                               class="form-check-input section-checkbox me-2"
                                               id="section_{{ \Illuminate\Support\Str::slug($sectionName) }}">
                                        <label class="fw-bold text-primary mb-0" for="section_{{ \Illuminate\Support\Str::slug($sectionName) }}">
                                            {{ $sectionName }}
                                        </label>
                                    </div>

                                    {{-- Permisos individuales --}}
                                    @foreach($permList as $perm)
                                        @php
                                            $partes = explode('.', $perm->name);
                                            $modulo = ucfirst($partes[0] ?? '');
                                            $accion = ucfirst($partes[1] ?? '');
                                            $accion = $accionesBonitas[$accion] ?? $accion;
                                        @endphp
                                        <div class="form-check mb-1">
                                            <input class="form-check-input section-permission-{{ \Illuminate\Support\Str::slug($sectionName) }}"
                                                   type="checkbox"
                                                   name="permissions[]"
                                                   value="{{ $perm->name }}"
                                                   id="perm_{{ \Illuminate\Support\Str::slug($perm->name) }}"
                                                {{ (is_array(old('permissions')) && in_array($perm->name, old('permissions'))) ? 'checked' : '' }}>
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


            {{-- Mensaje de error para permisos --}}
                    @error('permissions')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Botones --}}
    <div class="d-flex justify-content-center gap-4 mt-3">
        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-plus-circle"></i> Crear Rol
        </button>

        <button type="button" id="btnLimpiar" class="btn btn-warning px-4">
            <i class="bi bi-x-circle"></i> Limpiar
        </button>

        <a href="{{ route('roles.index') }}" class="btn btn-success px-4">
            <i class="bi bi-arrow-left"></i> Regresar
        </a>
    </div>

    </form>
    </div>

    {{-- Script para seleccionar todos los permisos --}}
    @push('scripts')
        <script>
            document.getElementById('selectAllPermisos').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
                checkboxes.forEach(cb => cb.checked = this.checked);
            });
        </script>
    @endpush
    @push('scripts')
        <script>
            // Seleccionar todos los permisos generales
            document.getElementById('selectAllPermisos').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
                checkboxes.forEach(cb => cb.checked = this.checked);

                // También actualizar los checkboxes de sección
                const sections = document.querySelectorAll('.section-checkbox');
                sections.forEach(sec => sec.checked = this.checked);
            });

            // Seleccionar todos los permisos por sección
            document.querySelectorAll('.section-checkbox').forEach(section => {
                section.addEventListener('change', function() {
                    const slug = this.id.replace('section_', '');
                    const permisos = document.querySelectorAll('.section-permission-' + slug);
                    permisos.forEach(cb => cb.checked = this.checked);
                });
            });
        </script>
    @endpush
    @push('scripts')
        <script>
            // Seleccionar todos los permisos global
            document.getElementById('selectAllPermisos').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
                checkboxes.forEach(cb => cb.checked = this.checked);

                const sections = document.querySelectorAll('.section-checkbox');
                sections.forEach(sec => sec.checked = this.checked);
            });

            // Seleccionar permisos por sección
            document.querySelectorAll('.section-checkbox').forEach(section => {
                section.addEventListener('change', function() {
                    const slug = this.id.replace('section_', '');
                    const permisos = document.querySelectorAll('.section-permission-' + slug);
                    permisos.forEach(cb => cb.checked = this.checked);
                });
            });

            // Botón Limpiar
            document.getElementById('btnLimpiar').addEventListener('click', function() {
                // Limpiar input nombre
                const inputName = document.querySelector('input[name="name"]');
                inputName.value = '';
                inputName.classList.remove('is-invalid');

                // Limpiar mensajes de error
                const errores = document.querySelectorAll('.invalid-feedback, .text-danger, .alert-danger ul');
                errores.forEach(e => e.remove());

                // Desmarcar checkbox global
                const selectAll = document.getElementById('selectAllPermisos');
                selectAll.checked = false;

                // Desmarcar checkboxes de secciones
                document.querySelectorAll('.section-checkbox').forEach(cb => cb.checked = false);

                // Desmarcar checkboxes individuales de permisos
                document.querySelectorAll('input[name="permissions[]"]').forEach(cb => cb.checked = false);
            });
        </script>
    @endpush

@endsection
