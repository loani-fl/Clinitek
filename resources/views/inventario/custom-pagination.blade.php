@php
    $currentPage = $inventarios->currentPage();
    $lastPage = $inventarios->lastPage();
    $onFirstPage = $inventarios->onFirstPage();
    $hasMorePages = $inventarios->hasMorePages();
    $from = $inventarios->firstItem();
    $to = $inventarios->lastItem();
    $total = $inventarios->total();
@endphp

<style>
.custom-pagination {
    display:flex;
    justify-content:center;
    gap:0.3rem;
    list-style:none;
    padding:0;
}
.custom-pagination .page-item.active .page-link { background:#007BFF; color:#fff; font-weight:600; cursor:default;}
</style>

<nav aria-label="Paginación">
    <ul class="pagination custom-pagination">
        <li class="page-item {{ $onFirstPage ? 'disabled' : '' }}">
            <a class="page-link" href="#" data-page="{{ $currentPage - 1 }}">Anterior</a>
        </li>

        @for ($i = 1; $i <= $lastPage; $i++)
            <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                <a class="page-link" href="#" data-page="{{ $i }}">{{ $i }}</a>
            </li>
        @endfor

        <li class="page-item {{ !$hasMorePages ? 'disabled' : '' }}">
            <a class="page-link" href="#" data-page="{{ $currentPage + 1 }}">Siguiente</a>
        </li>
    </ul>
</nav>
