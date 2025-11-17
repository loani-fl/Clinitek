@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm rounded-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Crear Rol</h3>
                <a href="{{ route('roles.index') }}" class="btn btn-light btn-sm">Regresar</a>
            </div>
            <div class="card-body">
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf

                    <!-- Nombre del rol -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del rol:</label>
                        <input type="text" name="name" class="form-control" required placeholder="Ingrese el nombre del rol">
                    </div>

                    <!-- Permisos -->
                    <div class="mb-3">
                        <label class="form-label">Permisos:</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="selectAll">
                            <label class="form-check-label" for="selectAll">
                                Seleccionar todos los permisos
                            </label>
                        </div>

                        <div class="row">
                            @foreach($permissions as $perm)
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input permiso" type="checkbox" name="permissions[]" value="{{ $perm->id }}" id="perm_{{ $perm->id }}">
                                        <label class="form-check-label" for="perm_{{ $perm->id }}">
                                            {{ $perm->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success">Crear Rol</button>
                </form>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            document.getElementById('selectAll').addEventListener('change', function() {
                let checked = this.checked;
                document.querySelectorAll('.permiso').forEach(function(checkbox) {
                    checkbox.checked = checked;
                });
            });
        </script>
    @endsection

@endsection

