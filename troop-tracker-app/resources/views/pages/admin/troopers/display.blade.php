@extends('layouts.base')

@section('content')

<x-table>
  <thead>
    <tr>
      <th>Name</th>
    </tr>
  </thead>
  <tbody>
    @foreach($troopers as $trooper)
    <tr>
      <td>{{ $trooper->name }}</td>
    </tr>
    @endforeach
  </tbody>
</x-table>

@endsection