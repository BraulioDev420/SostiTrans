<x-mail::message>
{{-- Encabezado personalizado --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# ¡Vaya!
@else
# ¡Hola!
@endif
@endif

{{-- Líneas de introducción --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Botón de acción --}}
@isset($actionText)
<?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
?>
<x-mail::button :url="$actionUrl" :color="$color">
{{ $actionText }}
</x-mail::button>
@endisset

{{-- Líneas finales --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Saludo final --}}
@if (! empty($salutation))
{{ $salutation }}
@else
Saludos,<br>
SostiTrans
@endif

{{-- Subcopy personalizada --}}
@isset($actionText)
<x-slot:subcopy>
Si tienes problemas al hacer clic en el botón "{{ $actionText }}", copia y pega la siguiente URL en tu navegador:<br>
<span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset
</x-mail::message>
