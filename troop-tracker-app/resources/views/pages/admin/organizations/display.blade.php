@extends('layouts.base')

@section('content')

<x-table>
  <thead>
    <tr>
      <th>
        Name
      </th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    @foreach($organizations as $organization)
    <tr>
      <td>
        @foreach(range(0, $organization->depth - 1) as $i)
        <i class="fa fa-fw"></i>
        @endforeach
        {{ $organization->name }}
      </td>
      <td>
        @can('update', $organization)
        YES
        @endcan
        {{--
        <x-link-button-update :url="route('admin.organizations.organization', ['organization'=>$organization])" />
        --}}
      </td>
    </tr>
    @endforeach
  </tbody>
  <tfoot>
    <tr>
      <td colspan="2">
        {{ $organizations->count() }} Organizations
      </td>
    </tr>
  </tfoot>
</x-table>

@endsection