<nav aria-label="Navegación de páginas">
    <ul class="pagination custom-pagination justify-content-center">

        {{-- Botón anterior --}}
        <li class="page-item {{ $onFirstPage ? 'disabled' : '' }}">
            <a class="page-link btn-paginate" data-page="{{ $currentPage - 1 }}" href="javascript:void(0)">
                Anterior
            </a>
        </li>

        {{-- Números --}}
        @for ($i = 1; $i <= $lastPage; $i++)
            <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                <a class="page-link btn-paginate" data-page="{{ $i }}" href="javascript:void(0)">
                    {{ $i }}
                </a>
            </li>
        @endfor

        {{-- Botón siguiente --}}
        <li class="page-item {{ !$hasMorePages ? 'disabled' : '' }}">
            <a class="page-link btn-paginate" data-page="{{ $currentPage + 1 }}" href="javascript:void(0)">
                Siguiente
            </a>
        </li>
    </ul>

    <div class="pagination-info text-center mt-2 text-muted">
        Mostrando del {{ $from ?? 0 }} al {{ $to ?? 0 }} de {{ $total ?? 0 }} resultados
    </div>
</nav>
