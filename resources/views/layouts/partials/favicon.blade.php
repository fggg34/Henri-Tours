@if($siteIcon = \App\Models\Setting::get('site_icon'))
    <link rel="icon" href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($siteIcon) }}" type="{{ str_ends_with($siteIcon, '.svg') ? 'image/svg+xml' : (str_ends_with($siteIcon, '.ico') ? 'image/x-icon' : 'image/png') }}">
@endif
