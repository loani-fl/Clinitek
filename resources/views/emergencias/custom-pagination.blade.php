{{-- resources/views/emergencias/custom-pagination.blade.php --}}
<style>
    .custom-pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0; /* Cambiado de 0.3rem a 0 */
        padding: 0;
        list-style: none;
    }
    
    .custom-pagination .page-item {
        list-style: none;
        margin: 0;
    }
    
    .custom-pagination .page-link {
        display: inline-block;
        padding: 0.5rem 0.75rem;
        border: 1px solid #dee2e6;
        border-radius: 0; /* Cambiado: sin redondeo por defecto */
        margin-left: -1px; /* Superpone los bordes */
        color: #007BFF;
        background-color: #fff;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        cursor: pointer;
        position: relative;
    }
    
    /* Redondear solo el primer botón (izquierda) */
    .custom-pagination .page-item:first-child .page-link {
        border-top-left-radius: 0.375rem;
        border-bottom-left-radius: 0.375rem;
        margin-left: 0;
    }
    
    /* Redondear solo el último botón (derecha) */
    .custom-pagination .page-item:last-child .page-link {
        border-top-right-radius: 0.375rem;
        border-bottom-right-radius: 0.375rem;
    }
    
    .custom-pagination .page-link:hover:not(.disabled) {
        background-color: #007BFF;
        color: #fff;
        border-color: #007BFF;
        z-index: 2; /* Eleva el botón al hacer hover */
    }
    
    .custom-pagination .page-item.active .page-link {
        background-color: #007BFF;
        color: #fff;
        border-color: #007BFF;
        font-weight: 600;
        cursor: default;
        z-index: 3; /* Mantiene el activo encima */
    }
    
    .custom-pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #e9ecef;
        border-color: #dee2e6;
        cursor: not-allowed;
        opacity: 0.6;
        pointer-events: none;
    }
    
    .pagination-info {
        text-align: center;
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 0.75rem;
    }
</style>

<nav aria-label="Navegación de páginas">
    <ul class="pagination custom-pagination justify-content-center">
        {{-- Botón Anterior --}}
        <li class="page-item {{ $onFirstPage ? 'disabled' : '' }}">
            <a class="page-link" href="?page={{ $currentPage - 1 }}" aria-label="Anterior">
                Anterior
            </a>
        </li>

        {{-- Números de página --}}
        @for ($i = 1; $i <= $lastPage; $i++)
            <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                <a class="page-link" href="?page={{ $i }}">{{ $i }}</a>
            </li>
        @endfor

        {{-- Botón Siguiente --}}
        <li class="page-item {{ !$hasMorePages ? 'disabled' : '' }}">
            <a class="page-link" href="?page={{ $currentPage + 1 }}" aria-label="Siguiente">
                Siguiente
            </a>
        </li>
    </ul>
    
    {{-- Información de resultados --}}
    <div class="pagination-info">
        Mostrando del {{ $from }} al {{ $to }} de {{ $total }} resultados
    </div>
</nav>