@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="custom-card shadow-sm">
            <div class="card-header text-center"
                 style="background-color:#fff; border-bottom:4px solid #91cfff;">
                <h2 class="mb-0 fw-bold" style="font-size:2rem;">Asignar rol de usuario</h2>
            </div>

            <div class="card-body text-center">

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="mb-4 d-flex justify-content-center">
                    <div class="user-card text-center p-3 rounded-4 shadow-sm">
                        <h3 class="fw-bold mb-2" style="font-size:1.5rem;">{{ $user->name }}</h3>
                        <span id="rol-seleccionado" class="badge bg-primary" style="font-size:1rem; padding:0.4em 0.8em;">
                        {{ $user->roles->first()?->name ?? 'Sin rol' }}
                    </span>
                    </div>
                </div>

                    <form action="{{ route('usuarios.asignar.update', $user->id) }}" method="POST">
                        @csrf

                        <h5 class="mb-3">Seleccione un Rol:</h5>

                        <div class="row justify-content-center">
                            @foreach ($roles as $role)
                                @php
                                    $icon = match($role->name) {
                                        'administrador' => 'bi-gear-fill',
                                        'medico' => 'bi-person-badge',
                                        'recepcion' => 'bi-person-badge-fill',
                                        default => 'bi-person-circle',
                                    };
                                @endphp

                                <div class="col-md-4 mb-3">
                                    <label class="w-100">
                                        <!-- Cambio aquí: name="role" (no array) -->
                                        <input type="radio" name="role" value="{{ $role->name }}" class="d-none"
                                            {{ $user->hasRole($role->name) ? 'checked' : '' }}>

                                        <div class="role-card p-3 text-center rounded-4 shadow-sm" onclick="seleccionarRol(this)">
                                            <i class="bi {{ $icon }}" style="font-size:2rem;"></i>
                                            <h5 class="mt-2">{{ ucfirst($role->name) }}</h5>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 d-flex justify-content-center gap-3">
                            <button type="submit" class="btn btn-primary px-4">Asignar Rol</button>
                            <a href="{{ route('usuarios.index') }}" class="btn btn-success px-4">Regresar</a>
                        </div>
                    </form>

                    <script>
                        function seleccionarRol(card) {
                            document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
                            card.classList.add('selected');
                            card.previousElementSibling.checked = true;

                            const rolNombre = card.querySelector('h5').textContent;
                            document.getElementById('rol-seleccionado').textContent = rolNombre;
                        }

                        document.addEventListener('DOMContentLoaded', () => {
                            document.querySelectorAll('input[name="role"]').forEach(input => {
                                if(input.checked) {
                                    input.nextElementSibling.classList.add('selected');
                                }
                            });
                        });
                    </script>
