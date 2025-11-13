@php($is_selected = old("clubs.{$club->id}.selected", $club->selected))

<div id="club-selection-{{ $club->id }}">
  <x-input-container>
    <x-input-checkbox :property="'clubs[' . $club->id . '][selected]'"
                      :label="$club->name"
                      :value="'1'"
                      :checked="$is_selected"
                      :spinner="$club->id"
                      data-club-id="{{ $club->id }}"
                      hx-post="{{ route('auth.register-htmx', ['club' => $club->id]) }}"
                      hx-target="#club-selection-{{ $club->id }}"
                      hx-swap="outerHTML"
                      hx-trigger="change"
                      hx-include="closest div"
                      hx-indicator="#spinner-{{ $club->id }}" />
  </x-input-container>

  @if($is_selected)
  <div class="club-{{ $club->id }} ps-4">
    <x-input-container>
      <x-label>
        {{ $club->identifier_display }}:
      </x-label>
      <x-input-text :property="'clubs.' . $club->id . '.identifier'" />
    </x-input-container>
    @if($club->squads->count() > 0)
    <x-input-container>
      <x-input-select :property="'clubs.' . $club->id . '.squad_id'"
                      :options="$club->squads->pluck('name', 'id')->toArray()"
                      :placeholder="'-- Select your Squad --'" />
    </x-input-container>
    @endif
  </div>
  @endif
</div>