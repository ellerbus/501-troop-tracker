@php($is_selected = old("clubs.{$club->id}.selected", $club->selected))

<div id="club-selection-{{ $club->id }}">
  <input type="hidden"
         name="clubs[{{ $club->id }}][selected]"
         value="0" />
  <input type="checkbox"
         id="club-id-{{$club->id}}"
         name="clubs[{{ $club->id }}][selected]"
         value="1"
         data-club-id="{{ $club->id }}"
         hx-post="{{ route('register-htmx', ['club' => $club->id]) }}"
         hx-target="#club-selection-{{ $club->id }}"
         hx-swap="outerHTML"
         hx-trigger="change"
         hx-include="closest div"
         hx-indicator="#spinner-{{ $club->id }}"
         @checked($is_selected?'checked':'') />

  <x-label for="club-id-{{$club->id}}">
    {{ $club->name }}
    <x-spinner :id="$club->id" />
  </x-label>
  @if($is_selected)
  <div class="club-{{ $club->id }}">
    <x-label>
      {{ $club->identifier_display }}:
    </x-label>
    <x-input type="text"
             :property="'clubs.' . $club->id . '.identifier'" />
    @if(count($club->squads) > 0)
    <x-select :property="'clubs.' . $club->id . '.squad_id'"
              :options="$club->squads->pluck('name', 'id')->toArray()"
              :placeholder="'-- Select your Squad --'" />
    @endif
  </div>
  @endif
</div>