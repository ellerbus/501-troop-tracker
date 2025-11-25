<x-table>
  <thead>
    <tr>
      <th>Assignment</th>
      <th>Moderator</th>
    </tr>
  </thead>
  @foreach ($organization_assignments as $organization)
  @php($trooper_assignment = $organization->trooper_assignments->first())
  <tr data-id="{{ $organization->id }}"
      data-parent-id="{{ $organization->parent_id }}">
    <td>
      @foreach(range(0, $organization->depth - 1) as $i)
      <i class="fa fa-fw"></i>
      @endforeach
      <label for="{{'moderators.'.$organization->id.'.selected'}}">
        {{ $organization->name }}
      </label>
    </td>
    <td class="cascade">
      <x-input-checkbox :property="'moderators.'.$organization->id.'.selected'"
                        :checked="$trooper_assignment->membership_role??'' == \App\Enums\MembershipRole::Moderator" />
    </td>
  </tr>
  @endforeach
</x-table>