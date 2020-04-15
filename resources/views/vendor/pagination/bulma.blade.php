<div class="has-margin-md">
    @if ($paginator->hasPages())
        <nav class="pagination is-centered" role="navigation" aria-label="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <a class="pagination-previous" disabled><x-fa name="chevron-left" library="solid"/> &nbsp; @lang('pagination.previous')</a>
            @else
                <a class="pagination-previous" href="{{ $paginator->previousPageUrl() }}"><x-fa name="chevron-left" library="solid"/> &nbsp; @lang('pagination.previous')</a>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a class="pagination-next" href="{{ $paginator->nextPageUrl() }}">@lang('pagination.next') &nbsp; <x-fa name="chevron-right" library="solid"/></a>
            @else
                <a class="pagination-next" disabled>@lang('pagination.next') &nbsp; <x-fa name="chevron-right" library="solid"/></a>
            @endif

            {{-- Pagination Elements --}}
            <ul class="pagination-list">
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li>
                            <span class="pagination-ellipsis">&hellip;</span>
                        </li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li><a class="pagination-link is-current"
                                       aria-label="@lang('pagination.goto_page') {{ $page }}">{{ $page }}</a>
                                </li>
                            @else
                                <li><a href="{{ $url }}" class="pagination-link"
                                       aria-label="@lang('pagination.goto_page') {{ $page }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </ul>

        </nav>
    @endif
</div>
