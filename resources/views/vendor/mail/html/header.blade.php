@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
{{-- <img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo"> --}}
<img src="{{ url('images/mail_logo.jpg') }}" class="logo" alt="EventRush Logo" style="height: 50px">

@else
{{ $slot }}
@endif
</a>
</td>
</tr>
