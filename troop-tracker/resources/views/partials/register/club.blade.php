<input type="checkbox"
       id="club-id-{{$club->id}}"
       data-club-id="{{ $club->id }}" />
<x-label for="club-id-{{$club->id}}">
  {{ $club->name }}
</x-label>
<div class="club-{{ $club->id }}">
  <x-label>
    {{ $club->db_identifier_display }}:
  </x-label>
  <x-input type="text"
           disabled="disabled"
           name="club_{{ $club->id }}" />
</div>
<br /><br />