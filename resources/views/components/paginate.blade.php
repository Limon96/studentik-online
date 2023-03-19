@if ($paginator->hasPages())
    <ul class="pagination">
        @if(!$paginator->onFirstPage())
            <li><a href="{{ $paginator->url(1) }}">|&lt;</a></li>
            <li><a href="{{ $paginator->previousPageUrl() }}">&lt;</a></li>
        @endif
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="active"><span>{{ $element }}</span></li>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active"><span>{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach
        @if(!$paginator->onLastPage())
            <li><a href="{{ $paginator->nextPageUrl() }}">&gt;</a></li>
            <li><a href="{{ $paginator->url($paginator->lastPage()) }}">&gt;|</a></li>
        @endif
    </ul>

@endif
