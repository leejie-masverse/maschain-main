@php
    $dot = $dot ?? false;
    $type = $type ?? 'brand';
    $wide = $wide ?? true;
    $rounded = $rounded ?? false;
@endphp
<span class="m-badge m-badge--{{ $type }} {{ $wide ? 'm-badge--wide' : '' }} {{ $rounded ? 'm-badge--rounded' : '' }}">
    {{ $slot }}
</span>
