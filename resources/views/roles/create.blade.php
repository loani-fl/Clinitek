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

        <h2 class="text-center fw-bold section-title mb-4">
            Crear Nuevo Rol
        </h2>

        {{-- Mensajes --}}
        @if(session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        {{-- Errores de validación --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('roles.store') }}" method="POST">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nombre del Rol:</label>
                    <input type="text" name="name" class="form-control" placeholder="Ej: administrador" value="{{ old('name') }}" required>
                </div>

                <div class="col-md-6 d-flex align-items-end justify-content-end">
                    <a href="{{ route('roles.index') }}" class="btn btn-success">
                        <i class="bi bi-arrow-left"></i> Regresar
                    </a>
                </div>
            </div>

            {{-- PREPARAR COLECCIÓN DE PERMISOS --}}
            @php
                // Soportar tanto $permissions como $permisos según cómo lo pase el controlador
                $allPermissions = null;
                if (isset($permissions) && $permissions) {
                    $allPermissions = collect($permissions);
                } elseif (isset($permisos) && $permisos) {
                    $allPermissions = collect($permisos);
                } else {
                    $allPermissions = collect();
                }

                // Si el controlador ya pasó las secciones (ej. $usuarios, $pacientes...), preferimos usarlas
                $useSectionsFromController = false;
                $controllerSections = [];
                if (isset($usuarios)) { $useSectionsFromController = true; $controllerSections['Usuarios'] = $usuarios; }
                if (isset($pacientes)) { $useSectionsFromController = true; $controllerSections['Pacientes'] = $pacientes; }
                if (isset($medicos)) { $useSectionsFromController = true; $controllerSections['Médicos'] = $medicos; }
                if (isset($empleado)) { $useSectionsFromController = true; $controllerSections['Empleados'] = $empleado; }
                if (isset($consultas)) { $useSectionsFromController = true; $controllerSections['Consultas'] = $consultas; }
                if (isset($controlPrenatal)) { $useSectionsFromController = true; $controllerSections['Control Prenatal'] = $controlPrenatal; }
                if (isset($recetas)) { $useSectionsFromController = true; $controllerSections['Recetas'] = $recetas; }
                if (isset($rayosX)) { $useSectionsFromController = true; $controllerSections['Rayos X'] = $rayosX; }
                if (isset($ultrasonidos)) { $useSectionsFromController = true; $controllerSections['Ultrasonidos'] = $ultrasonidos; }
                if (isset($inventario)) { $useSectionsFromController = true; $controllerSections['Inventario'] = $inventario; }
                if (isset($farmacias)) { $useSectionsFromController = true; $controllerSections['Farmacias'] = $farmacias; }
                if (isset($hospitalizacion)) { $useSectionsFromController = true; $controllerSections['Hospitalización'] = $hospitalizacion; }
                if (isset($diagnosticos)) { $useSectionsFromController = true; $controllerSections['Diagnósticos'] = $diagnosticos; }
                if (isset($emergencias)) { $useSectionsFromController = true; $controllerSections['Emergencias'] = $emergencias; }
                if (isset($puestos)) { $useSectionsFromController = true; $controllerSections['Puestos'] = $puestos; }
                if (isset($examenes)) { $useSectionsFromController = true; $controllerSections['Exámenes'] = $examenes; }
                if (isset($sesiones)) { $useSectionsFromController = true; $controllerSections['Sesiones'] = $sesiones; }
                if (isset($dashboard)) { $useSectionsFromController = true; $controllerSections['Dashboard'] = $dashboard; }

                // Si no pasaron secciones desde el controlador, crear secciones automáticas por prefijo
                if (!$useSectionsFromController) {
                    $controllerSections = [
                        'Usuarios' => $allPermissions->filter(fn($p) => str_starts_with($p->name, 'usuarios.')),
                        'Pacientes' => $allPermissions->filter(fn($p) => str_starts_with($p->name, 'pacientes.')),
                        'Médicos' => $allPermissions->filter(fn($p) => str_starts_with($p->name, 'medicos.')),
                        'Empleados' => $allPermissions->filter(fn($p) => str_starts_with($p->name, 'empleado.')),
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
                }
            @endphp

            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="selectAllPermisos">
                        <label class="form-check-label fw-bold" for="selectAllPermisos">Seleccionar todos los permisos</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h5 class="section-title">Permisos del Rol</h5>

                    <div class="row">
                        @foreach($controllerSections as $sectionName => $permList)
                            @if($permList && $permList->count() > 0)
                                <div class="col-md-6 mb-4">
                                    <h6 class="fw-bold text-primary">{{ $sectionName }}</h6>

                                    @foreach($permList as $perm)
                                        <div class="form-check mb-1">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   name="permissions[]"
                                                   value="{{ $perm->name }}"
                                                   id="perm_{{ \Illuminate\Support\Str::slug($perm->name) }}">

                                            <label class="form-check-label" for="perm_{{ \Illuminate\Support\Str::slug($perm->name) }}">
                                                {{ ucfirst(str_replace(['.', '_'], [' ', ' '], $perm->name)) }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center gap-4 mt-3">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-plus-circle"></i> Crear Rol
                </button>

                <a href="{{ route('roles.index') }}" class="btn btn-success px-4">
                    <i class="bi bi-arrow-left"></i> Regresar
                </a>
            </div>
        </form>
    </div>

@endsection
