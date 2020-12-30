<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{ URL::asset('assets/images/logoconci.png')}}" class="logo" alt="Conciflex Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
