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
    {{ $attributes->merge(['class' => 'group flex flex-col justify-between p-6 sm:p-7 rounded-2xl border border-border-soft bg-white transition-all duration-200 hover:shadow-md hover:border-forest/40 hover:-translate-y-0.5 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-forest']) }}
>
    <div>
        {{-- Card Header: Icon & Badge --}}
        <div class="flex items-center justify-between gap-3 mb-5">
            <div class="w-13 h-13 rounded-xl bg-mint/30 flex items-center justify-center text-forest group-hover:bg-forest group-hover:text-white transition-colors duration-200 border border-mint/50">
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
    <div class="mt-6 pt-4 border-t border-border-soft/60 flex items-center justify-between text-sm font-semibold text-forest group-hover:text-teal transition-colors">
        <span>Buka Portal</span>
        <div class="w-8 h-8 rounded-full bg-soft flex items-center justify-center group-hover:bg-forest group-hover:text-white transition-all duration-200">
            <x-icon name="arrow-right" class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-0.5" />
        </div>
    </div>
</a>
