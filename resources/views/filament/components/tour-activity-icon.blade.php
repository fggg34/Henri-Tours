@props(['record'])

@php
    $svg = $record->getIconSvgContent();
    $url = $record->icon_url ?? null;
@endphp

@if($svg)
    <div style="width:32px;height:32px;color:#374151;display:flex;align-items:center;justify-content:center">
        {!! preg_replace('/<svg/', '<svg style="width:100%;height:100%;display:block"', $svg, 1) !!}
    </div>
@elseif($url)
    <img src="{{ url($url) }}" alt="" style="width:32px;height:32px;object-fit:contain" loading="lazy">
@else
    <span class="text-gray-400">—</span>
@endif
