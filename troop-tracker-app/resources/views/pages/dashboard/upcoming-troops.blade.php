<x-table>
  <thead>
    <tr>
      <th></th>
      <th>Event Name</th>
      <th class="text-center">Date</th>
      <th>Pending Costume</th>
    </tr>
  </thead>
  <tbody>
    @forelse($upcoming_troops as $troop)
    <tr>
      <td>
        {{--
        <img src="{{ url($troop->image_path_sm) }}" />
        --}}
      </td>
      <td>
        {{ $troop->name }}
      </td>
      <td class="text-center text-nowrap">
        <x-date-format :value="$troop->starts_at"
                       :format="'M j, Y'" />
      </td>
      <td>
        @foreach($troop->event_troopers as $trooper)
        @if(isset($trooper->club_costume) && $trooper->club_costume->name != 'N/A')
        <x-costume-name :organization="$trooper->club_costume->organization->name"
                        :costume="$trooper->club_costume->name" />
        @else
        <span class="text-muted">N/A</span>
        @endif
        @endforeach
      </td>
    </tr>
    @empty
    <tr>
      <td colspan="4">
        No Upcoming Troops ... Yet!
      </td>
    </tr>
    @endforelse
  </tbody>
</x-table>