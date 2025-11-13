@props(['club'=>null, 'costume'=>null])
<span>
  @if(isset($club))
  ( {{ $club }} )
  @endif
  {{ $costume }}
</span>