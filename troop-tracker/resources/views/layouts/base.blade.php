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

  <!-- Setup Variable -->
  <script>
    var forumURL = "https://www.fl501st.com/boards/";
    var placeholder = 1196;
    var squadIDList = [0, 1, 2, 3, 4, 5];
    var clubArray = ["pRebel", "pDroid", "pMando", "pOther", "pSG", "pDE"];
    var clubDBLimitArray = ["limitRebels", "limitDroid", "limitMando", "limitOther", "limitSG", "limitDE"];
    var clubDB3Array = ["rebelforum", "mandoid", "sgid", "de_id"];
    // Clear limits
    function clearLimit() {
      $("#limitRebels").val(500);
      $("#limitDroid").val(500);
      $("#limitMando").val(500);
      $("#limitOther").val(500);
      $("#limitSG").val(500);
      $("#limitDE").val(500);
    }
  </script>

  @include('partials.scripts')

  <script>
    $(function () {
      $("#datepicker").datetimepicker();
      $("#datepicker2").datetimepicker();
      $("#datepicker3").datetimepicker();
      $("#datepicker4").datetimepicker();
    });
  </script>
</head>

<body class="floridadark">

  <div class="tm-container">
    <div class="tm-text-white tm-page-header-container">
      <img src="images/logo.png" />
    </div>
    <div class="tm-main-content">
      <section class="tm-section">
        @include('partials.navbar', ['current_page' => View::yieldContent('current_page')])
        @include('partials.messages')

        <div class="dashboard-row"></div>
        @yield('content')

      </section>

      @include('partials.footer')
      @include('partials.scripts')
      @yield('page-script')
</body>

</html>