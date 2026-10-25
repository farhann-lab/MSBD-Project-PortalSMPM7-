@props([
    'title',
    'description',
    'href' => '#',
    'icon' => 'computer-desktop',
    'badge' => null,
])

<a 
    href="{{ $href }}" 
    target="_self"
    {{ $attributes->merge(['class' => 'group relative flex flex-col justify-between p-6 sm:p-7 rounded-2xl border border-border-soft bg-white transition-all duration-300 hover:shadow-xl hover:border-forest/50 hover:-translate-y-1.5 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-forest overflow-hidden']) }}
>
    {{-- Subtle top accent highlight on hover --}}
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-forest via-sage to-mint opacity-0 group-hover:opacity-100 transition-opacity duration-300" aria-hidden="true"></div>

    <div>
        {{-- Card Header: Icon & Badge --}}
        <div class="flex items-center justify-between gap-3 mb-5">
            <div class="w-12 h-12 rounded-xl bg-forest/10 flex items-center justify-center text-forest group-hover:bg-forest group-hover:text-white transition-all duration-300 border border-forest/15 shadow-2xs">
                <x-icon :name="$icon" class="w-6 h-6" />
            </div>

            @if($badge)
                <x-badge variant="sage" size="sm">
                    {{ $badge }}
                </x-badge>
            @endif
        </div>

        {{-- Card Title & Description --}}
        <h3 class="text-xl font-bold text-darkgreen group-hover:text-forest transition-colors leading-snug mb-2">
            {{ $title }}
        </h3>

        <p class="text-sm text-muted-text leading-relaxed">
            {{ $description }}
        </p>
    </div>

    {{-- Link CTA with Arrow --}}
    <div class="mt-6 pt-4 border-t border-border-soft/70 flex items-center justify-between text-sm font-semibold text-forest group-hover:text-darkgreen transition-colors">
        <span>Buka Portal Akses</span>
        <div class="w-8 h-8 rounded-full bg-forest/10 flex items-center justify-center text-forest group-hover:bg-forest group-hover:text-white transition-all duration-300">
            <x-icon name="arrow-right" class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" />
        </div>
    </div>
</a>
