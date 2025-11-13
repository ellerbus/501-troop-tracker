@extends('layouts.base')

@section('content')

<x-page-title>
  Manage Account
</x-page-title>

<x-slim-container>

  <!-- Profile Information -->
  <x-card :label="'Profile Information'">
    <div hx-get="{{ route('account.profile-htmx') }}"
         hx-trigger="load"
         hx-swap="outerHTML">
      <x-loading />
    </div>
  </x-card>

  <!-- Notification Settings -->
  <x-card :label="'Notification Settings'">
    <div hx-get="{{ route('account.notifications-htmx') }}"
         hx-trigger="load"
         hx-swap="outerHTML">
      <x-loading />
    </div>
  </x-card>

  <!-- Favorite Costumes -->
  <x-card :label="'Favorite Costumes'">
    <div hx-get="{{ route('account.favorite-costumes-htmx') }}"
         hx-trigger="load"
         hx-swap="outerHTML">
      <x-loading />
    </div>
  </x-card>

  <!-- Donations & Support -->
  <x-card :label="'Donations & Support'">
    <div hx-get="{{ route('support-htmx') }}"
         hx-trigger="load"
         hx-swap="outerHTML">
      <x-loading />
    </div>
  </x-card>

</x-slim-container>

{{--
<!-- Danger Zone -->
<x-card :label="'Danger Zone'"
        :danger="true">
  <button class="btn btn-warning mb-2">Change Password</button><br>
  <button class="btn btn-danger">Deactivate Account</button>
</x-card>
--}}
@endsection