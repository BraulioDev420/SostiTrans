@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{ asset('images/logo.jpg') }}" class="logo" alt="SostiTrans Logo"  width="55"
height="55" class="rounded-circle border border-light shadow-sm">

@else
{{ $slot }}
@endif
</a>
</td>
</tr>
