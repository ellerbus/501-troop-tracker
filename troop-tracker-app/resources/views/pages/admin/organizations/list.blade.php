@extends('layouts.base')

@section('content')

<x-table>
  <thead>
    <tr>
      <th>
        Name
      </th>
      <th style="width: 32px;"></th>
      <th style="width: 128px;"></th>
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
        @if(Auth::user()->isAdministrator() || $organization->trooper_assignments->count() > 0)
        <x-link-button-update :url="route('admin.organizations.update', ['organization'=>$organization])" />
        @endif
      </td>
      <td>
        @can('create', \App\Models\Organization::class)
        @if($organization->type != \App\Enums\OrganizationType::Unit)
        <x-link-button-create :url="route('admin.organizations.create', ['parent'=>$organization])">
          @if($organization->type == \App\Enums\OrganizationType::Organization)
          Region
          @endif
          @if($organization->type == \App\Enums\OrganizationType::Region)
          Unit
          @endif
        </x-link-button-create>
        @endif
        @endcan
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