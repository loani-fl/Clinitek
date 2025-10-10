{{-- resources/views/sesiones/custom-pagination.blade.php --}}
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