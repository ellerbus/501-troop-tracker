<input type="checkbox"
       id="club-id-{{$club->id}}"
       name="clubs[{{ $club->id }}][selected]"
       value="1"
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
           name="clubs[{{ $club->id }}][identifier]" />
  @if(count($club->squads) > 0)
  <br />
  <br />
  <select name="clubs[{{ $club->id }}][squad_id]"
          class="form-select"
          disabled>
    <option value="">-- Select your Squad --</option>
    @foreach ($club->squads as $squad)
    <option value="{{ $squad->id }}">
      {{ $squad->name }}
    </option>
    @endforeach
  </select>
  @endif
</div>
<br /><br />