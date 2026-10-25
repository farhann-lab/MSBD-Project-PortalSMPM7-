@props([
    'pageTitle' => 'Halaman Sedang Dipersiapkan',
    'kicker' => 'Phase 3 Milestone',
    'description' => 'Halaman ini merupakan bagian dari rute publik yang akan diimplementasikan penuh pada Phase 3 (Page Structure & Visual Implementation).',
    'breadcrumbs' => [],
])

<x-layouts.public :title="$pageTitle" :description="$description">

    {{-- Breadcrumb --}}
    @if(!empty($breadcrumbs))
        <div class="bg-white border-b border-border-soft">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
                <x-breadcrumb :items="$breadcrumbs" />
            </div>
        </div>
    @endif

    {{-- Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 flex-1 flex items-center justify-center">
        <div class="bg-white rounded-2xl border border-border-soft shadow-xs p-8 sm:p-12 max-w-xl w-full text-center space-y-5">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-forest/10 text-forest mx-auto">
                <x-icon name="building-office" class="w-8 h-8" />
            </div>

            <div class="space-y-2">
                <x-badge variant="sage" size="sm">{{ $kicker }}</x-badge>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-darkgreen tracking-tight">
                    {{ $pageTitle }}
                </h1>
                <p class="text-sm text-muted-text leading-relaxed">
                    {{ $description }}
                </p>
            </div>

            <div class="pt-4 flex flex-wrap items-center justify-center gap-3">
                <x-button href="{{ url('/layout-preview') }}" variant="primary" icon="arrow-left">
                    Kembali ke Pratinjau Shell
                </x-button>
                <x-button href="{{ url('/components-preview') }}" variant="outline">
                    Showcase Komponen
                </x-button>
            </div>
        </div>
    </div>

</x-layouts.public>
