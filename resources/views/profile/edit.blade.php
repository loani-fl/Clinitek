@extends('layouts.app')

@section('title', 'Foto de Perfil')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 200px);">
    <div class="profile-card">
        
        {{-- Header del card --}}
        <div class="profile-card-header">
            <div class="profile-icon-bg">
                <i class="bi bi-person-circle"></i>
            </div>
            <h4 class="profile-title">Mi Perfil</h4>
        </div>

        <div class="profile-card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px;">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Foto de Perfil --}}
                <div class="text-center mb-4">
                    <div class="position-relative d-inline-block">
                        @if(auth()->user()->photo)
                            <img src="{{ asset(auth()->user()->photo) }}?v={{ time() }}" 
                                 alt="Foto de perfil" 
                                 class="profile-photo-display" 
                                 id="profilePhotoPreview">
                        @else
                            <div class="profile-photo-display profile-placeholder" id="profilePhotoPreview">
                                {{ strtoupper(substr(auth()->user()->name ?? auth()->user()->email, 0, 2)) }}
                            </div>
                        @endif
                        
                        {{-- Botón flotante de cámara --}}
                        <label for="photo" class="camera-button">
                            <i class="bi bi-camera-fill"></i>
                        </label>
                    </div>

                    <input type="file" 
                           id="photo" 
                           name="photo" 
                           accept="image/*" 
                           style="display: none;" 
                           onchange="previewPhoto(event)">
                    
                    @error('photo')
                        <div class="text-danger mt-2">
                            <small><i class="bi bi-exclamation-circle"></i> {{ $message }}</small>
                        </div>
                    @enderror
                </div>

                {{-- Información del usuario (Solo lectura) --}}
                <div class="info-card">
                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-person-fill"></i>
                            <span>Nombre</span>
                        </div>
                        <div class="info-value">{{ auth()->user()->name ?? 'Sin nombre' }}</div>
                    </div>
                    <div class="info-divider"></div>
                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-envelope-fill"></i>
                            <span>Correo</span>
                        </div>
                        <div class="info-value">{{ auth()->user()->email }}</div>
                    </div>
                </div>

                {{-- Botones de acción --}}
                <div class="action-buttons">
                    <a href="{{ route('inicio') }}" class="btn-custom btn-secondary-custom">
                        <i class="bi bi-x-circle"></i>
                        <span>Cancelar</span>
                    </a>
                    <button type="submit" class="btn-custom btn-primary-custom">
                        <i class="bi bi-check-circle"></i>
                        <span>Guardar</span>
                    </button>
                </div>

                {{-- Botón de eliminar foto (si existe) --}}
                @if(auth()->user()->photo)
                <div class="text-center mt-3">
                    <button type="button" 
                            class="btn-link-custom" 
                            onclick="removePhoto()">
                        <i class="bi bi-trash"></i> Eliminar foto de perfil
                    </button>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>

<style>
    /* Card principal */
    .profile-card {
        width: 100%;
        max-width: 400px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .profile-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
    }

    /* Header con gradiente */
    .profile-card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 30px 20px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .profile-card-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.3; }
    }

    .profile-icon-bg {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        backdrop-filter: blur(10px);
    }

    .profile-icon-bg i {
        font-size: 2rem;
        color: white;
    }

    .profile-title {
        color: white;
        font-weight: 700;
        margin: 0;
        font-size: 1.5rem;
        position: relative;
        z-index: 1;
    }

    /* Body del card */
    .profile-card-body {
        padding: 35px 30px;
    }

    /* Foto de perfil */
    .profile-photo-display {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid #667eea;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        transition: all 0.3s ease;
    }

    .profile-photo-display:hover {
        transform: scale(1.05);
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
    }

    .profile-placeholder {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: 700;
    }

    /* Botón de cámara */
    .camera-button {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 42px;
        height: 42px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.5);
        border: 3px solid white;
    }

    .camera-button i {
        color: white;
        font-size: 1.1rem;
    }

    .camera-button:hover {
        transform: scale(1.15) rotate(5deg);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.7);
    }

    /* Card de información */
    .info-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
        padding: 20px;
        margin-top: 25px;
        border: 1px solid #dee2e6;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
    }

    .info-label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.9rem;
        color: #495057;
        font-weight: 600;
    }

    .info-label i {
        color: #667eea;
        font-size: 1.1rem;
    }

  .info-value {
    font-size: 0.9rem;
    color: #212529;
    font-weight: 500;
    text-align: right;
    white-space: nowrap; /* Evita que el texto se rompa */
    overflow: hidden;    /* Oculta exceso si es muy largo */
    text-overflow: ellipsis; /* Muestra "..." si no cabe */
    max-width: 100%;     /* Ocupa todo el espacio disponible */
}


    .info-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, #ced4da, transparent);
        margin: 5px 0;
    }

    /* Botones de acción */
    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 25px;
    }

    .btn-custom {
        flex: 1;
        border: none;
        border-radius: 12px;
        padding: 12px 20px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary-custom {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
    }

    .btn-secondary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
        text-decoration: none;
        color: white;
    }

    .btn-danger-custom {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
    }

    .btn-danger-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
    }

    .btn-custom:active {
        transform: translateY(0);
    }

    /* Botón de enlace para eliminar */
    .btn-link-custom {
        background: none;
        border: none;
        color: #dc3545;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 8px 15px;
        border-radius: 8px;
    }

    .btn-link-custom:hover {
        background: #fce8e6;
        color: #c82333;
    }

    .btn-link-custom i {
        margin-right: 5px;
    }

    /* Responsive */
    @media (max-width: 576px) {
        .profile-card {
            max-width: 95%;
            margin: 10px;
        }

        .profile-card-body {
            padding: 25px 20px;
        }

        .profile-photo-display {
            width: 120px;
            height: 120px;
        }

        .info-value {
            font-size: 0.85rem;
            max-width: 50%;
        }

        .profile-title {
            font-size: 1.3rem;
        }
    }
</style>

<script>
function previewPhoto(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('profilePhotoPreview');
            preview.outerHTML = `<img src="${e.target.result}" alt="Preview" class="profile-photo-display" id="profilePhotoPreview">`;
        }
        reader.readAsDataURL(file);
    }
}

function removePhoto() {
    Swal.fire({
        title: '¿Eliminar foto?',
        text: "Tu foto de perfil será eliminada permanentemente",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-check-circle"></i> Sí, eliminar',
        cancelButtonText: '<i class="bi bi-x-circle"></i> Cancelar',
        customClass: {
            confirmButton: 'btn btn-danger mx-1',
            cancelButton: 'btn btn-secondary mx-1'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('{{ route("profile.remove-photo") }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Eliminada!',
                        text: 'Tu foto ha sido eliminada correctamente',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '{{ route("inicio") }}';
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo eliminar la foto'
                });
            });
        }
    });
}
</script>
@endsection