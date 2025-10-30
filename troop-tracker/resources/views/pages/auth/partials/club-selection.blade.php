<div id="club-selection-{{ $club->id }}">

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
         @checked(old("clubs.{$club->id}.selected", $club->selected)?'checked':'') />

  <x-label for="club-id-{{$club->id}}">
    {{ $club->name }}
    <span id="spinner-{{ $club->id }}"
          class="htmx-indicator"
          style="margin-left: 8px;">
      <i class="fa fa-spinner fa-spin"></i>
    </span>

  </x-label>
  @if($club->selected)
  <div class="club-{{ $club->id }}">
    <x-label>
      {{ $club->db_identifier_display }}:
    </x-label>
    <x-input type="text"
             disabled="disabled"
             :property="'clubs.' . $club->id . '.identifier'" />
    @if(count($club->squads) > 0)
    <br /><br />
    <x-select :property="'clubs.' . $club->id . '.squad_id'"
              :options="$club->squads->pluck('name', 'id')->toArray()"
              :placeholder="'-- Select your Squad --'" />
    @endif
  </div>
  @endif
</div>