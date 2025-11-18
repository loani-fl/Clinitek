@extends('layouts.app')

@section('content')

    <style>
        .custom-card {
            max-width: 1000px;
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
            width: 800px;
            height: 800px;
            background-image: url('/images/logo2.jpg');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.15;
            transform: translate(-50%, -50%);
            pointer-events: none;
            z-index: 0;
        }
        .text-danger {
            font-weight: 600;
            font-size: 0.875rem;
        }
    </style>

    <div class="custom-card">
        <div class="mb-4 text-center" style="border-bottom: 3px solid #007BFF;">
            <h2 class="fw-bold text-black mb-0">Crear Rol</h2>
        </div>

        <form action="{{ route('roles.store') }}" method="POST" novalidate>
            @csrf

            <!-- Nombre del rol -->
            <div class="mb-3">
                <label for="name" class="form-label">Nombre del rol: <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" required placeholder="Ingrese el nombre del rol">
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Permisos -->
            <div class="mb-3">
                <label class="form-label">Permisos:</label>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="selectAll">
                    <label class="form-check-label" for="selectAll">Seleccionar todos los permisos</label>
                </div>

                <div class="row">
                    @foreach($permissions as $perm)
                        <div class="col-md-4 col-sm-6">
                            <div class="form-check">
                                <input class="form-check-input permiso" type="checkbox" name="permissions[]" value="{{ $perm->id }}" id="perm_{{ $perm->id }}">
                                <label class="form-check-label" for="perm_{{ $perm->id }}">{{ $perm->name }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="d-flex justify-content-center gap-3 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Crear Rol
                </button>
                <a href="{{ route('roles.index') }}" class="btn btn-success">
                    <i class="bi bi-arrow-left"></i> Regresar
                </a>
            </div>
        </form>
    </div>

    @section('scripts')
        <script>
            // Seleccionar todos los permisos
            document.getElementById('selectAll').addEventListener('change', function() {
                let checked = this.checked;
                document.querySelectorAll('.permiso').forEach(function(checkbox) {
                    checkbox.checked = checked;
                });
            });
        </script>
    @endsection

@endsection
