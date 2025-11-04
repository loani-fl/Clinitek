<nav aria-label="Navegación de páginas">
    <ul class="pagination custom-pagination justify-content-center">
        <li class="page-item {{ $onFirstPage ? 'disabled' : '' }}">
            <a class="page-link" href="?page={{ $currentPage - 1 }}">Anterior</a>
        </li>

        @for ($i = 1; $i <= $lastPage; $i++)
            <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                <a class="page-link" href="?page={{ $i }}">{{ $i }}</a>
            </li>
        @endfor

        <li class="page-item {{ !$hasMorePages ? 'disabled' : '' }}">
            <a class="page-link" href="?page={{ $currentPage + 1 }}">Siguiente</a>
        </li>
    </ul>

    <div class="pagination-info text-center mt-2 text-muted">
        Mostrando del {{ $from ?? 0 }} al {{ $to ?? 0 }} de {{ $total ?? 0 }} resultados
    </div>
</nav>
