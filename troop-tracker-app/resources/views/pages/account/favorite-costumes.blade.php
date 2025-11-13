<x-transmission-bar :id="'favorite-costumes'" />

<div id="favorite-costumes-form-container">

  <form method="POST"
        novalidate="novalidate">
    @csrf

    <x-input-container id="club-select-container">
      @if($clubs->count() == 0)
      <x-message :type="'danger'">
        You do not have any assigned clubs
      </x-message>
      @else
      <x-input-select :property="'club_id'"
                      :options="$clubs->pluck('name', 'id')->toArray()"
                      :value="$selected_club->id ?? -1"
                      :placeholder="'-- Select your Club --'"
                      hx-get="{{ route('account.favorite-costumes-htmx') }}"
                      hx-trigger="change"
                      hx-select="#costume-select-container"
                      hx-target="#costume-select-container"
                      hx-indicator="#transmission-bar-favorite-costumes" />
      @endif
    </x-input-container>

    <x-input-container id="costume-select-container">
      @if(!empty($selected_club))
      <x-input-select :property="'costume_id'"
                      :options="$costumes"
                      :value="-1"
                      :placeholder="'-- Select your Costume --'"
                      hx-post="{{ route('account.favorite-costumes-htmx') }}"
                      hx-select="#favorite-costumes-table"
                      hx-target="#favorite-costumes-table"
                      hx-swap="outerHTML"
                      hx-indicator="#transmission-bar-favorite-costumes"
                      hx-include="closest form" />
      @endif
    </x-input-container>
  </form>

  <x-table id="favorite-costumes-table">
    <thead>
      <tr>
        <th>
          Costume
        </th>
        <th class="text-end">
          Remove
        </th>
      </tr>
    </thead>
    @forelse($favorites as $favorite)
    <tr>
      <td>
        {{ $favorite->getCostumeName() }}
      </td>
      <td class="text-end">
        <x-button class="btn-outline-danger"
                  hx-delete="{{ route('account.favorite-costumes-htmx', ['costume_id' => $favorite->costumeid]) }}"
                  hx-select="#favorite-costumes-table"
                  hx-target="#favorite-costumes-table"
                  hx-swap="outerHTML"
                  hx-indicator="#transmission-bar-favorite-costumes">
          <i class="fa fw fa-times"></i>
        </x-button>
      </td>
    </tr>
    @empty
    <tr>
      <td colspan="2">
        No Favorites Yet
      </td>
    </tr>
    @endforelse
  </x-table>
</div>