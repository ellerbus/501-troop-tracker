<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <!-- Meta Data -->
  <meta charset="UTF-8" />
  <meta name="viewport"
        content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible"
        content="ie=edge" />
  <meta name="csrf-token"
        content="{{ csrf_token() }}">

  <!-- Title -->
  <title>501st Florida Garrison - Troop Tracker</title>

  @include('partials.styles')
</head>

<body class="bg-black d-flex flex-column min-vh-100">

  <div class="container">
    <div class="text-center py-3">
      <img src=" {{ url('images/logo.png') }}"
           alt="501st Florida Garrison Logo"
           class="img-fluid" />
    </div>
  </div>

  <div class="container rounded-3 shadow-sm p-4 main-content">
    @include('partials.navbar')
    @include('partials.bread-crumbs')
    @include('partials.messages')
    <div class="row dashboard-row"></div>

    @yield('content')

    @include('partials.footer')
  </div>

  @include('partials.scripts')
  @yield('page-script')

</body>

</html>