@extends('layouts.base')

@section('content')

<x-table>
  <thead>
    <tr>
      <th>
        Name
      </th>
      <th style="width: 32px;"></th>
      <th style="width: 128px;">
        <x-link-button-create :url="route('admin.notices.create')">
          Notice
        </x-link-button-create>
      </th>
    </tr>
  </thead>
  <tbody>
    @foreach($notices as $notice)
    <tr>
      <td>
        {{ $notice->title }}
      </td>
      <td>
        {{--
        @if(Auth::user()->isAdministrator() || $notice->trooper_assignments->count() > 0)
        <x-link-button-update :url="route('admin.notices.update', ['notice'=>$notice])" />
        @endif
        --}}
      </td>
      <td>
        {{--
        @can('create', \App\Models\Notice::class)
        @if($notice->type != \App\Enums\NoticeType::Unit)
        <x-link-button-create :url="route('admin.notices.create', ['parent'=>$notice])">
          @if($notice->type == \App\Enums\NoticeType::Notice)
          Region
          @endif
          @if($notice->type == \App\Enums\NoticeType::Region)
          Unit
          @endif
        </x-link-button-create>
        @endif
        @endcan
        --}}
      </td>
    </tr>
    @endforeach
  </tbody>
  <tfoot>
    <tr>
      <td colspan="2">
        {{ $notices->count() }} Notices
      </td>
    </tr>
  </tfoot>
</x-table>

@endsection