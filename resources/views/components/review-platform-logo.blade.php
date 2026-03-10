@props(['platform' => null, 'url' => null])
@php
    $platform = $platform ?? '';
    $hasUrl = !empty($url);
@endphp
@if($platform)
    @if($hasUrl)
        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="absolute top-5 right-5 w-9 h-9 flex items-center justify-center overflow-hidden bg-white hover:border-gray-300 transition-colors" aria-label="View review on {{ $platform }}">
    @else
        <div class="absolute top-5 right-5 w-9 h-9 rounded-full flex items-center justify-center overflow-hidden bg-white border border-gray-200">
    @endif
    @switch($platform)
        @case('Tripadvisor')
            <svg class="w-9 h-9 p-0.5" viewBox="0 0 128 128" xmlns="http://www.w3.org/2000/svg"><circle cx="64" cy="64" r="64" fill="#11AD87"/><circle cx="41.8" cy="66.2" r="3.7" fill="#FFF"/><circle cx="86.3" cy="66.2" r="3.7" fill="#FFF"/><path fill="#FFF" d="M104.2,53.2c0.9-3.2,2.4-6.2,4.4-8.9l-14.8,0c-8.9-5.5-19.1-8.4-29.6-8.2c-10.7-0.2-21.3,2.7-30.4,8.3l-13.9,0c1.9,2.7,3.4,5.6,4.3,8.8c-7.3,9.9-5.2,23.8,4.7,31c9.6,7.1,23,5.3,30.5-4l4.7,7.1l4.8-7.1c7.6,9.7,21.5,11.3,31.2,3.8C109.5,76.5,111.4,62.9,104.2,53.2z M85.6,44.3C74.3,44.6,65,53.4,64.2,64.8C63.4,53.3,54,44.4,42.5,44.2c6.8-2.8,14.2-4.3,21.6-4.2C71.5,39.9,78.8,41.3,85.6,44.3z M42.1,84.1c-9.8,0-17.7-7.9-17.7-17.7s7.9-17.7,17.7-17.7c9.8,0,17.7,7.9,17.7,17.7c0,0,0,0,0,0C59.8,76.2,51.8,84.1,42.1,84.1z M92.5,83.1c-9.2,3.4-19.4-1.3-22.8-10.5v0c-3.4-9.2,1.3-19.4,10.5-22.8c9.2-3.4,19.4,1.3,22.8,10.5C106.4,69.5,101.7,79.7,92.5,83.1z"/><path fill="#FFF" d="M41.8,55.2c-6.1,0-11,4.9-11,11c0,6.1,4.9,11,11,11c6.1,0,11-4.9,11-11c0,0,0,0,0,0C52.7,60.1,47.8,55.2,41.8,55.2z M41.8,73.4c-4,0-7.2-3.2-7.2-7.2c0-4,3.2-7.2,7.2-7.2s7.2,3.2,7.2,7.2c0,0,0,0,0,0C49,70.1,45.8,73.3,41.8,73.4z"/><path fill="#FFF" d="M86.3,55.2c-6.1,0-11,4.9-11,11s4.9,11,11,11c6.1,0,11-4.9,11-11c0,0,0,0,0,0C97.2,60.1,92.3,55.2,86.3,55.2z M86.3,73.4c-4,0-7.2-3.2-7.2-7.2c0-4,3.2-7.2,7.2-7.2c4,0,7.2,3.2,7.2,7.2c0,0,0,0,0,0C93.5,70.1,90.3,73.4,86.3,73.4z"/></svg>
            @break
        @case('Getyourguide')
            <svg class="w-9 h-9 p-1" viewBox="0 0 56 56" xmlns="http://www.w3.org/2000/svg"><path fill="#F53" d="M43.163 0H12.837C5.747 0-.001 5.747-.001 12.836v30.328C-.001 50.254 5.748 56 12.837 56h30.326C50.253 56 56 50.253 56 43.164V12.836C55.999 5.746 50.252 0 43.163 0"/><path fill="#FFF" d="M28.039 8c-7.783 0-13.805 5.177-13.805 12.744h6.258C20.492 16.823 23.738 14 28 14c4.264 0 7.508 2.823 7.508 6.744h6.256C41.764 13.177 35.783 8 28.04 8zM17.364 29.451c1.876 0 3.362-1.451 3.362-3.373 0-1.922-1.486-3.373-3.362-3.373-1.878 0-3.364 1.451-3.364 3.373 0 1.922 1.486 3.373 3.364 3.373zm-3.13 1.96v3.843C14.234 42.587 19.67 48 26.358 48c4.183 0 7.86-2.118 10.012-5.334v4.745H42v-16H26.904v5.49h6.218C32.537 39.803 29.917 42 26.827 42c-3.481 0-6.335-2.707-6.335-6.746v-3.842h-6.258z"/></path></svg>
            @break
        @case('Tourradar')
            <img src="{{ asset('images/platforms/tourradar.png') }}" alt="TourRadar" class="w-full h-full object-contain">
            @break
        @case('TourHq')
            <svg class="w-9 h-9 p-1.5" viewBox="0 0 128 128" xmlns="http://www.w3.org/2000/svg"><circle cx="64" cy="64" r="60" fill="#4A90D9"/><text x="64" y="82" font-family="Arial,sans-serif" font-size="48" font-weight="bold" fill="#FFF" text-anchor="middle">TH</text></svg>
            @break
        @default
            <div class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center">
                <i class="fa-solid fa-star text-gray-400 text-xs"></i>
            </div>
    @endswitch
    @if($hasUrl)
        </a>
    @else
        </div>
    @endif
@endif
