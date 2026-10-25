@props([
    'paginator' => null,
    'currentPage' => 1,
    'lastPage' => 1,
    'baseUrl' => null,
])

@php
    // If a Laravel paginator object was passed
    if ($paginator && is_object($paginator) && method_exists($paginator, 'currentPage')) {
        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();
        $hasPages = $paginator->hasPages();
        $prevUrl = $paginator->previousPageUrl();
        $nextUrl = $paginator->nextPageUrl();
        $elements = method_exists($paginator, 'elements') ? $paginator->elements() : null;
    } else {
        $hasPages = $lastPage > 1;
        $baseUrl = $baseUrl ?? request()->url();
        $queryParams = request()->query();

        $getCustomUrl = function($page) use ($baseUrl, $queryParams) {
            $params = array_merge($queryParams, ['page' => $page]);
            return $baseUrl . '?' . http_build_query($params);
        };

        $prevUrl = $currentPage > 1 ? $getCustomUrl($currentPage - 1) : null;
        $nextUrl = $currentPage < $lastPage ? $getCustomUrl($currentPage + 1) : null;
        $elements = null;
    }
@endphp

@if($hasPages)
    <nav role="navigation" aria-label="Pagination Navigation" {{ $attributes->merge(['class' => 'flex items-center justify-between gap-2 py-4']) }}>
        {{-- Mobile View: Previous, Indicator, Next --}}
        <div class="flex items-center justify-between w-full sm:hidden">
            @if($prevUrl)
                <x-button :href="$prevUrl" variant="outline" size="sm" icon="chevron-left" iconPosition="left">
                    Sebelumnya
                </x-button>
            @else
                <span class="inline-flex items-center gap-1.5 min-h-9 px-3.5 py-1.5 text-xs rounded-lg border border-border-soft bg-soft text-muted-text/60 cursor-not-allowed">
                    <x-icon name="chevron-left" class="w-3.5 h-3.5" />
                    Sebelumnya
                </span>
            @endif

            <span class="text-xs font-semibold text-darkgreen px-2">
                {{ $currentPage }} / {{ $lastPage }}
            </span>

            @if($nextUrl)
                <x-button :href="$nextUrl" variant="outline" size="sm" icon="chevron-right" iconPosition="right">
                    Berikutnya
                </x-button>
            @else
                <span class="inline-flex items-center gap-1.5 min-h-9 px-3.5 py-1.5 text-xs rounded-lg border border-border-soft bg-soft text-muted-text/60 cursor-not-allowed">
                    Berikutnya
                    <x-icon name="chevron-right" class="w-3.5 h-3.5" />
                </span>
            @endif
        </div>

        {{-- Desktop & Tablet View --}}
        <div class="hidden sm:flex sm:items-center sm:justify-between w-full">
            <div>
                <p class="text-xs text-muted-text">
                    Menampilkan halaman <span class="font-bold text-darkgreen">{{ $currentPage }}</span> dari <span class="font-bold text-darkgreen">{{ $lastPage }}</span>
                </p>
            </div>

            <div class="inline-flex items-center gap-1">
                {{-- Previous Page Link --}}
                @if($prevUrl)
                    <a href="{{ $prevUrl }}" rel="prev" class="inline-flex items-center justify-center min-h-9 px-3 py-1.5 text-xs font-medium rounded-lg border border-border-soft bg-white text-darkgreen hover:bg-soft hover:border-sage transition-colors focus-visible:outline-2 focus-visible:outline-forest">
                        <x-icon name="chevron-left" class="w-3.5 h-3.5 mr-1" />
                        Sebelumnya
                    </a>
                @else
                    <span aria-disabled="true" class="inline-flex items-center justify-center min-h-9 px-3 py-1.5 text-xs font-medium rounded-lg border border-border-soft bg-soft text-muted-text/50 cursor-not-allowed">
                        <x-icon name="chevron-left" class="w-3.5 h-3.5 mr-1" />
                        Sebelumnya
                    </span>
                @endif

                {{-- Page Numbers --}}
                @php
                    $startPage = max(1, $currentPage - 2);
                    $endPage = min($lastPage, $currentPage + 2);
                @endphp

                @if($startPage > 1)
                    <a href="{{ $paginator ? $paginator->url(1) : $getCustomUrl(1) }}" class="inline-flex items-center justify-center w-9 h-9 text-xs font-medium rounded-lg border border-border-soft bg-white text-darkgreen hover:bg-soft transition-colors">
                        1
                    </a>
                    @if($startPage > 2)
                        <span class="px-1 text-muted-text">…</span>
                    @endif
                @endif

                @for($page = $startPage; $page <= $endPage; $page++)
                    @if($page == $currentPage)
                        <span aria-current="page" class="inline-flex items-center justify-center w-9 h-9 text-xs font-bold rounded-lg bg-forest text-white shadow-xs">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $paginator ? $paginator->url($page) : $getCustomUrl($page) }}" class="inline-flex items-center justify-center w-9 h-9 text-xs font-medium rounded-lg border border-border-soft bg-white text-darkgreen hover:bg-soft transition-colors focus-visible:outline-2 focus-visible:outline-forest">
                            {{ $page }}
                        </a>
                    @endif
                @endfor

                @if($endPage < $lastPage)
                    @if($endPage < $lastPage - 1)
                        <span class="px-1 text-muted-text">…</span>
                    @endif
                    <a href="{{ $paginator ? $paginator->url($lastPage) : $getCustomUrl($lastPage) }}" class="inline-flex items-center justify-center w-9 h-9 text-xs font-medium rounded-lg border border-border-soft bg-white text-darkgreen hover:bg-soft transition-colors">
                        {{ $lastPage }}
                    </a>
                @endif

                {{-- Next Page Link --}}
                @if($nextUrl)
                    <a href="{{ $nextUrl }}" rel="next" class="inline-flex items-center justify-center min-h-9 px-3 py-1.5 text-xs font-medium rounded-lg border border-border-soft bg-white text-darkgreen hover:bg-soft hover:border-sage transition-colors focus-visible:outline-2 focus-visible:outline-forest">
                        Berikutnya
                        <x-icon name="chevron-right" class="w-3.5 h-3.5 ml-1" />
                    </a>
                @else
                    <span aria-disabled="true" class="inline-flex items-center justify-center min-h-9 px-3 py-1.5 text-xs font-medium rounded-lg border border-border-soft bg-soft text-muted-text/50 cursor-not-allowed">
                        Berikutnya
                        <x-icon name="chevron-right" class="w-3.5 h-3.5 ml-1" />
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif
