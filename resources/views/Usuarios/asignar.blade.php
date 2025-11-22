@extends('layouts.app')

@section('content')
    <div class="container mt-4">

        <div class="custom-card shadow-sm">
            {{-- Título centrado --}}
            <div class="card-header text-center"
                 style="background-color:#fff; border-bottom:4px solid #91cfff;">
                <h2 class="mb-0 fw-bold" style="font-size:2rem;">Asignar roles de usuario</h2>
            </div>

            <div class="card-body text-center">

                {{-- Mensajes de éxito --}}
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                {{-- Nombre del usuario con rol debajo --}}
                {{-- Nombre del usuario con rol debajo --}}
                <div class="mb-4 d-flex justify-content-center">
                    <div class="user-card text-center p-3 rounded-4 shadow-sm">
                        <h3 class="fw-bold mb-2" style="font-size:1.5rem;">{{ $user->name }}</h3>
                        <span id="rol-seleccionado" class="badge bg-primary" style="font-size:1rem; padding:0.4em 0.8em;">
            {{ $user->roles->first()?->name ?? 'Sin rol' }}
        </span>
                    </div>
                </div>

                <style>
                    .user-card {
                        background-color: #f8f9fa; /* color de fondo suave */
                        border: 2px solid #91cfff; /* borde azul claro */
                        max-width: 300px; /* más compacta */
                        width: 100%;
                        transition: all 0.3s;
                    }

                    .user-card:hover {
                        background-color: #e9f5ff; /* sutil cambio al pasar el mouse */
                        transform: translateY(-2px);
                    }
                </style>



                <form action="{{ route('usuarios.asignar.update', $user->id) }}" method="POST">
                    @csrf


                    <h5 class="mb-3">Seleccione un Rol:</h5>

                    <div class="row justify-content-center">
                        @foreach ($roles as $role)
                            @php
                                // Definir íconos por rol
                                $icon = match($role->name) {
                                    'administrador' => 'bi-gear-fill',
                                    'medico' => 'bi-person-badge',
                                    'paciente' => 'bi-person-fill',
                                    'recepcionista' => 'bi-person-badge-fill',
                                    default => 'bi-person-circle',
                                };
                            @endphp
                            <div class="col-md-4 mb-3">
                                <label class="w-100">
                                    <input type="radio" name="roles[]" value="{{ $role->name }}" class="d-none"
                                            {{ $user->hasRole($role->name) ? 'checked' : '' }}>

                                    <div class="role-card p-3 text-center rounded-4 shadow-sm" onclick="seleccionarRol(this)">
                                        <i class="bi {{ $icon }}" style="font-size:2rem;"></i>
                                        <h5 class="mt-2">{{ ucfirst($role->name) }}</h5>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    {{-- Botones centrados --}}
                    <div class="mt-4 d-flex justify-content-center gap-3">
                        <button type="submit" class="btn btn-primary px-4">
                            Asignar Rol
                        </button>
                        <a href="{{ route('usuarios.index') }}" class="btn btn-success px-4">
                            Regresar
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <style>
        .custom-card {
            max-width: 800px;
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
            width: 600px;
            height: 600px;
            background-image: url('/images/logo2.jpg');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.1;
            transform: translate(-50%, -50%);
            pointer-events: none;
            z-index: 0;
        }

        .role-card {
            cursor: pointer;
            transition: all 0.3s;
            border: 1px solid #ccc;
            background-color: #fff;
        }

        .role-card:hover {
            background-color: #e9f5ff;
            border-color: #2196f3;
            transform: translateY(-3px);
        }

        .role-card.selected {
            border: 2px solid #2196f3;
            background-color: #e9f5ff;
        }
    </style>

    <script>
        function seleccionarRol(card) {
            // Quitar la clase selected de todas
            document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
            // Marcar esta card
            card.classList.add('selected');
            // Marcar el input radio correspondiente
            card.previousElementSibling.checked = true;

            // Actualizar el badge del rol debajo del nombre del usuario
            const rolNombre = card.querySelector('h5').textContent;
            document.getElementById('rol-seleccionado').textContent = rolNombre;
        }

        // Inicializar selección al cargar la página
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('input[name="roles[]"]').forEach(input => {
                if(input.checked) {
                    input.nextElementSibling.classList.add('selected');
                }
            });
        });
    </script>
@endsection


