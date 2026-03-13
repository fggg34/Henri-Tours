@php
    $locale = app()->getLocale();
    $content = \App\Models\Setting::getTranslated('page_terms_content', $locale, '');
    $seoTitle = \App\Models\Setting::getTranslated('page_terms_seo_title', $locale, '');
    $seoDesc = \App\Models\Setting::getTranslated('page_terms_seo_description', $locale, '');
@endphp
@extends('layouts.site')

@section('title', $seoTitle ?: ('Terms & Cancellation Policy - ' . config('app.name')))
@section('description', $seoDesc ?: 'Our booking terms and cancellation policy.')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Terms & Cancellation Policy</h1>

    @if($content)
    <div class="blog-content">
        {!! $content !!}
    </div>
    @else
    <p class="text-gray-500">Content not yet added. Please add content from the admin panel.</p>
    @endif
</div>
@endsection
