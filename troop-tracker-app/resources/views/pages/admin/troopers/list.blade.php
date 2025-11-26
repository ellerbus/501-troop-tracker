@extends('layouts.base')

@section('content')

<x-table>
  <thead>
    <tr>
      <th>
        Name
        <br />
        Email
      </th>
      <th>Status</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    @foreach($troopers as $trooper)
    <tr>
      <td>
        {{ $trooper->name }}
        <br />
        {{ $trooper->email }}
      </td>
      <td>{{ $trooper->membership_status->name }}</td>
      <td>
        <x-link-button-update :url="route('admin.troopers.update', ['trooper'=>$trooper])" />
      </td>
    </tr>
    @endforeach
  </tbody>
  <tfoot>
    <tr>
      <td colspan="3">
        {{ $troopers->count() }} Troopers
      </td>
    </tr>
  </tfoot>
</x-table>

@endsection