@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'GSManager')
<img src="https://gsmanager.ru/img/notification-logo.png" class="logo" alt="GSManager Logo">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
