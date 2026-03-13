@props(['activity', 'class' => 'w-8 h-8'])

@if($activity->icon ?? null)
    @php $svg = $activity->getIconSvgContent(); @endphp
    @if($svg)
        <span class="{{ $class }} flex-shrink-0 flex items-center justify-center [&>svg]:w-full [&>svg]:h-full [&>svg]:object-contain" title="{{ $activity->title }}">{!! $svg !!}</span>
    @else
        {{-- Use object tag with explicit type to force SVG rendering (avoids Content-Type issues) --}}
        <object data="{{ $activity->icon_url }}" type="image/svg+xml" class="{{ $class }} flex-shrink-0" title="{{ $activity->title }}" aria-hidden="true">
            <span class="{{ $class }} flex-shrink-0 rounded flex items-center justify-center bg-slate-100 text-slate-500" aria-hidden="true">
                <i class="fa-solid fa-sparkles text-sm"></i>
            </span>
        </object>
    @endif
@endif
