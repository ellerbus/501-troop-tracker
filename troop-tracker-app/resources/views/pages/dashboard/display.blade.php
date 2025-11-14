@extends('layouts.base')

@section('content')

<x-page-title>
  Dashboard
</x-page-title>

<x-card :label="'Trooper Achievements'">
  <div hx-get="{{ route('dashboard.achievements-htmx', ['trooper_id' => $trooper->id]) }}"
       hx-trigger="load"
       hx-swap="outerHTML">
    <x-loading />
  </div>
</x-card>

{{--
@include('pages.dashboard.overview')
@include('pages.dashboard.troop-breakdown')

<!-- Navigation Tabs -->
<ul class="nav nav-tabs mb-4"
    role="tablist">
  <li class="nav-item">
    <a class="nav-link active"
       data-bs-toggle="tab"
       href="#upcoming">
      Upcoming Troops
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link"
       data-bs-toggle="tab"
       href="#history">
      Troop History
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link"
       data-bs-toggle="tab"
       href="#awards">Awards
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link"
       data-bs-toggle="tab"
       href="#photos">Tagged Photos
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link"
       data-bs-toggle="tab"
       href="#donations">Donations
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link"
       data-bs-toggle="tab"
       href="#costumes">
      Costumes
    </a>
  </li>
</ul>

<!-- Tab Content -->
<div class="tab-content">

  <!-- Donations & Support -->
  <div class="tab-pane fade show active"
       id="upcoming">
    <x-card :label="'Upcoming Troops'">
      <div hx-get="{{ route('dashboard.upcoming-troops-htmx', ['trooper_id' => $trooper->id]) }}"
           hx-trigger="load"
           hx-swap="outerHTML">
        <x-loading />
      </div>
    </x-card>
  </div>

  <!-- Troop History -->
  <div class="tab-pane fade"
       id="history">
    <x-card :label="'Troop History'">
      <div hx-get="{{ route('dashboard.historical-troops-htmx', ['trooper_id' => $trooper->id]) }}"
           hx-trigger="load"
           hx-swap="outerHTML">
        <x-loading />
      </div>
    </x-card>
  </div>

  <!-- Awards -->
  <div class="tab-pane fade"
       id="awards">
    <x-card :label="'Awards'">
      <div hx-get="{{ route('dashboard.trooper-awards-htmx', ['trooper_id' => $trooper->id]) }}"
           hx-trigger="load"
           hx-swap="outerHTML">
        <x-loading />
      </div>
    </x-card>
  </div>

  <!-- Tagged Photos -->
  <div class="tab-pane fade"
       id="photos">
    <div class="card mb-4">
      <div class="card-header">Tagged Photos</div>
      <div class="card-body">
        <p>No tagged photos to display.</p>
      </div>
    </div>
  </div>

  <!-- Donations -->
  <div class="tab-pane fade"
       id="donations">
    <div class="card mb-4">
      <div class="card-header">Donations</div>
      <div class="card-body">
        <p>No donations yet!</p>
      </div>
    </div>
  </div>

  <!-- Costumes -->
  <div class="tab-pane fade"
       id="costumes">
    <div class="card mb-4">
      <div class="card-header">Costumes</div>
      <div class="card-body">
        <h5>501st</h5>
        <div class="row mb-3">
          <div class="col-md-6">
            <p>Imperial Crew: Bridge Crew</p>
            <img src="https://via.placeholder.com/150"
                 class="img-fluid rounded"
                 alt="Costume">
          </div>
          <div class="col-md-6">
            <p>Shadow Guard: TFU</p>
            <img src="https://via.placeholder.com/150"
                 class="img-fluid rounded"
                 alt="Costume">
          </div>
        </div>
        <h5>Rebel Legion</h5>
        <p>No Rebel Legion costumes to display.</p>
        <h5>Mando Mercs</h5>
        <p>No Mando Mercs costumes to display.</p>
        <h5>Saber Guild</h5>
        <p>No Saber Guild costumes to display.</p>
        <h5>Droid Builders</h5>
        <p>No Droid Builder droids to display.</p>
      </div>
    </div>
  </div>

</div>
--}}

@endsection