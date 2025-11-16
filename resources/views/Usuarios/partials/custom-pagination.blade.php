@if($usuarios->hasPages())
<nav aria-label="Navegación de páginas">
    <ul class="pagination custom-pagination justify-content-center">
        {{-- Botón anterior --}}
        <li class="page-item {{ $usuarios->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link btn-paginate" data-page="{{ $usuarios->currentPage() - 1 }}" href="javascript:void(0)">Anterior</a>
        </li>

        {{-- Números de página --}}
        @for ($i = 1; $i <= $usuarios->lastPage(); $i++)
            <li class="page-item {{ $i == $usuarios->currentPage() ? 'active' : '' }}">
                <a class="page-link btn-paginate" data-page="{{ $i }}" href="javascript:void(0)">{{ $i }}</a>
            </li>
        @endfor

        {{-- Botón siguiente --}}
        <li class="page-item {{ !$usuarios->hasMorePages() ? 'disabled' : '' }}">
            <a class="page-link btn-paginate" data-page="{{ $usuarios->currentPage() + 1 }}" href="javascript:void(0)">Siguiente</a>
        </li>
    </ul>

    <div class="pagination-info text-center mt-2 text-muted">
        Mostrando del {{ $usuarios->firstItem() ?? 0 }} al {{ $usuarios->lastItem() ?? 0 }} de {{ $usuarios->total() ?? 0 }} resultados
    </div>
</nav>
@endif
