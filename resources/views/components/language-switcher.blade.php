@php
    $locales = ['en', 'zh_CN', 'fr', 'de', 'he', 'it', 'mt', 'es'];
    $localeFlags = ['en' => 'gb', 'zh_CN' => 'cn', 'fr' => 'fr', 'de' => 'de', 'he' => 'il', 'it' => 'it', 'mt' => 'mt', 'es' => 'es'];
    $currentLocale = app()->getLocale();
    $currentFlag = $localeFlags[$currentLocale] ?? 'gb';
@endphp
<div class="relative" x-data="{ langOpen: false }" @click.away="langOpen = false">
    <button type="button" @click="langOpen = !langOpen"
        class="flex items-center gap-1.5 px-2 py-1.5 text-sm text-gray-700 hover:text-brand-navy transition-colors rounded"
        aria-expanded="false" aria-haspopup="true" aria-label="{{ __('navigation.language') ?? 'Language' }}">
        <img src="https://flagcdn.com/w20/{{ $currentFlag }}.png" alt="" class="w-5 h-[15px] object-cover rounded-sm" width="20" height="15" loading="lazy" />
        <svg class="w-3 h-3 text-brand-navy" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
    </button>
    <div x-show="langOpen" x-cloak x-transition
         class="absolute right-0 top-full mt-1 p-2 bg-white rounded-md shadow-lg border border-gray-100 ring-1 ring-black ring-opacity-5 z-50 flex flex-wrap gap-1 max-w-[140px]">
        @foreach($locales as $locale)
            @php $flag = $localeFlags[$locale] ?? 'gb'; @endphp
            <a href="{{ route('locale.switch', $locale) }}"
               title="{{ __('locales.' . $locale) }}"
               class="flex items-center justify-center p-1.5 rounded hover:bg-gray-50 {{ $locale === $currentLocale ? 'ring-2 ring-brand-navy ring-offset-1' : '' }}">
                <img src="https://flagcdn.com/w40/{{ $flag }}.png" alt="{{ __('locales.' . $locale) }}" class="w-7 h-5 object-cover rounded-sm" width="28" height="20" loading="lazy" />
            </a>
        @endforeach
    </div>
</div>
