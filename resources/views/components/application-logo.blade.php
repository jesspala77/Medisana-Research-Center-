@php
    $gsgLogo = asset('images/brand/global-synergia-group-logo.png');
    $synnexusLogo = asset('images/outreach/synnexus-logo.png');
@endphp

<div class="flex items-center gap-3" {{ $attributes }}>
    <img src="{{ $gsgLogo }}" alt="Global Synergia Group" class="h-10 w-auto object-contain" />
    <img src="{{ $synnexusLogo }}" alt="SynNexus" class="h-10 w-auto object-contain" />
</div>
