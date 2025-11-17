@extends('layouts.app')

@section('content')
    <div class="container mt-4">

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h4 class="mb-0">Asignar Roles y Permisos a {{ $user->name }}</h4>
            </div>

            <div class="card-body">

                {{-- Mostrar mensajes --}}
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('usuarios.updateRoles', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        {{-- Roles --}}
                        <div class="col-md-6">
                            <h5 class="mb-3">Roles</h5>

                            @foreach($roles as $rol)
                                <div class="form-check mb-2">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="roles[]"
                                           value="{{ $rol->name }}"
                                        {{ $user->hasRole($rol->name) ? 'checked' : '' }}>

                                    <label class="form-check-label">
                                        {{ ucfirst($rol->name) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        {{-- Permisos --}}
                        <div class="col-md-6">
                            <h5 class="mb-3">Permisos</h5>

                            @foreach($permisos as $permiso)
                                <div class="form-check mb-2">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="permisos[]"
                                           value="{{ $permiso->name }}"
                                        {{ $user->hasPermissionTo($permiso->name) ? 'checked' : '' }}>

                                    <label class="form-check-label">
                                        {{ ucfirst($permiso->name) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <button class="btn btn-primary mt-4 px-4">Guardar Cambios</button>
                </form>
            </div>
        </div>

    </div>
@endsection

