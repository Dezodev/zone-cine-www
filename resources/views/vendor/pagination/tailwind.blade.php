@if ($paginator->hasPages())
  <nav class="pagination-nav" role="navigation" aria-label="Pagination">
    <div class="pagination-bar">

      {{-- Précédent --}}
      @if ($paginator->onFirstPage())
        <span class="pagination-bar__arrow pagination-bar__arrow--disabled" aria-disabled="true">
          <x-gmsi-o-arrow_back />
        </span>
      @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-bar__arrow" aria-label="Page précédente">
          <x-gmsi-o-arrow_back />
        </a>
      @endif

      {{-- Info page --}}
      <span class="pagination-bar__info">
        Page <strong>{{ $paginator->currentPage() }}</strong> sur <strong>{{ $paginator->lastPage() }}</strong>
      </span>

      {{-- Suivant --}}
      @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-bar__arrow" aria-label="Page suivante">
          <x-gmsi-o-arrow_forward />
        </a>
      @else
        <span class="pagination-bar__arrow pagination-bar__arrow--disabled" aria-disabled="true">
          <x-gmsi-o-arrow_forward />
        </span>
      @endif

    </div>
  </nav>
@endif
