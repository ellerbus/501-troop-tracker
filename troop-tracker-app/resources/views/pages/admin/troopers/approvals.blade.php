@extends('layouts.base')

@section('content')
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
  @foreach ($troopers as $trooper)
  <div class="col">
    <div class="card h-100 shadow-sm">
      <div class="card-header text-uppercase">
        {{ $trooper->name }}
      </div>
      <div class="card-body">
        <dl class="row mb-0">
          <dt class="col-4">Email:</dt>
          <dd class="col-8">{{ $trooper->email }}</dd>

          <dt class="col-4">Phone:</dt>
          <dd class="col-8">{{ $trooper->phone ?? 'n/a' }}</dd>

          @if(config('tracker.has_squads'))
          <dt class="col-4">Squad:</dt>
          <dd class="col-8">{{ $trooper->unit->name ?? 'n/a' }}</dd>
          @endif

        </dl>
        {{--
        <dl>
          @foreach($trooper->organizations as $organization)
          <dt class="col-4">{{ $organization->name }}:</dt>
          <dd class="col-8">{{ $organization->pivot_identifier }}</dd>
          @endforeach
        </dl>
        --}}

      </div>
      <div class="card-footer d-flex justify-content-between">
        <button class="btn btn-danger btn-sm">Reject</button>
        <button class="btn btn-success btn-sm">Approve</button>
        {{--
        <form method="POST"
              action="{{ route('trooper.approve', $trooper) }}">
          @csrf
          <button class="btn btn-success btn-sm">Approve</button>
        </form>
        <form method="POST"
              action="{{ route('trooper.reject', $trooper) }}">
          @csrf
        </form>
        --}}
      </div>
    </div>
  </div>
  @endforeach
</div>
@endsection