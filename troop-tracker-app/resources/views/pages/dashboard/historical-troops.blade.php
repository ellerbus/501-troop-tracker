<x-table>
  <thead>
    <tr>
      <th></th>
      <th>Event Name</th>
      <th class="text-center">Date</th>
      <th>Attended Costume</th>
    </tr>
  </thead>
  <tbody>
    @forelse($historical_troops as $troop)
    <tr>
      <td>
        {{--
        <img src="{{ url($troop->image_path_sm) }}" />
        --}}
      </td>
      <td>
        {{ $troop->event->name }}
      </td>
      <td class="text-center text-nowrap">
        <x-date-format :value="$troop->event->starts_at"
                       :format="'M j, Y'" />
      </td>
      <td>
        @if(isset($troop->club_costume))
        <x-costume-name :club="$troop->club_costume->club->name"
                        :costume="$troop->club_costume->name" />
        @else
        <span class="text-muted">N/A</span>
        @endif
      </td>
    </tr>
    @empty
    <tr>
      <td colspan="4">
        No Troops ... Yet!
      </td>
    </tr>
    @endforelse
  </tbody>
</x-table>