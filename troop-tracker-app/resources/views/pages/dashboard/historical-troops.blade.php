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
        <img src="{{ url($troop->image_path_sm) }}" />
      </td>
      <td>
        {{ $troop->event_name }}
      </td>
      <td class="text-center text-nowrap">
        <x-date-format :value="$troop->start_date"
                       :format="'M j, Y'" />
      </td>
      <td>
        <x-costume-name :club="$troop->club_name"
                        :costume="$troop->costume_name" />
      </td>
    </tr>
    @empty
    <tr>
      <td colspan="3">
        No Troops ... Yet!
      </td>
    </tr>
    @endforelse
  </tbody>
</x-table>