@extends('layouts.base')
@section('current_page', 'register')

@section('content')
<x-page-title :title="'Request Access'" />

<div name="requestAccessFormArea"
     id="requestAccessFormArea">

  <x-message>
    New to the 501st and/or {{ config('tracker.forum.display_name') }}? Or are you solely a member of another club? Use
    this form below
    to start signing up for troops. Command Staff will need to approve your account prior to use.
  </x-message>

  <form action="{{ route('register') }}"
        name="requestAccessForm"
        id="requestAccessForm"
        method="POST"
        novalidate="novalidate">


    <x-label>
      First & Last Name (use a nickname if you wish to remain anonymous):
    </x-label>
    <x-input type="text"
             required
             :property="'name'" />
    <br /><br />


    <x-label>
      Phone (Optional):
    </x-label>
    <x-input type="text"
             required
             :property="'phone'" />
    <br /><br />


    <x-label>
      Account Type:
    </x-label>
    <x-select :property="'account_type'"
              :options="['1'=>'Regular', '4'=>'Handler']" />
    <br /><br />


    <x-label :value="config('tracker.forum.display_name') . ' Username:'" />
    <x-input type="text"
             required
             autofocus
             :property="'forum_username'" />
    <br /><br />


    <x-label :value="config('tracker.forum.display_name') . ' Password:'" />
    <x-input type="text"
             required
             :property="'forum_password'" />
    <br /><br />

    <!-- 
    <x-label>
      Squad/Club:
    </x-label>
    <select name="squad"
            id="squad">
      <option value="1">Everglades Squad</option>
      <option value="2">Makaze Squad</option>
      <option value="3">Parjai Squad</option>
      <option value="4">Squad 7</option>
      <option value="5">Tampa Bay Squad</option>
      <option value="6">Rebel Legion</option>
      <option value="7">Droid Builders</option>
      <option value="8">Mando Mercs</option>
      <option value="9">Other</option>
      <option value="10">Saber Guild</option>
      <option value="13">Dark Empire</option>
      <option value="0">Florida Garrison / 501st Visitor</option>
    </select>
    <br /><br /> -->

    <p>
      Select your associated clubs below.
    </p>

    @foreach ($clubs as $club)
    @include('partials.register.club', ['club' => $club])
    @endforeach
    <!-- 
    <x-label>
      Rebel Legion Forum Username (if applicable):
    </x-label>
    <x-input type="text"
             :property="'rebel_legion_username'" />
    <br /><br />


    <x-label>
      Mando Mercs CAT # (if applicable):
    </x-label>
    <x-input type="text"
             :property="'mando_mercs_id'" />
    <br /><br />


    <x-label>
      Saber Guild SG # (if applicable):
    </x-label>
    <x-input type="text"
             :property="'saber_guild_id'" />
    <br /><br />


    <x-label>
      Dark Empire # (if applicable):
    </x-label>
    <x-input type="text"
             :property="'dark_empire_id'" />
    <br /><br /> -->


    <x-submit-button>
      Register
    </x-submit-button>
    <br />
    <p>
      If you are a dual member, you will only need one account.
    </p>

  </form>
</div>
@endsection

@section('page-script')
<script type="text/javascript">
  $(document).ready(function () {
    // $('#account_type').change(function () {
    //   const selected = $(this).val();

    //   if (selected == '1') {
    //     $('#tkid_container').show();
    //   } else if (selected == '4') {
    //     $('#tkid_container').hide();
    //   }
    // });

    // // Optional: trigger on page load to set initial state
    // $('#account_type').trigger('change');
    $('input[type="checkbox"][data-club-id]').each(function () {
      const $checkbox = $(this);
      const clubId = $checkbox.data('club-id');
      const $targetDiv = $('.club-' + clubId);
      const $inputField = $targetDiv.find('input');

      // Initial state
      if ($checkbox.is(':checked')) {
        $targetDiv.show();
        $inputField.prop('disabled', false);
      } else {
        $targetDiv.hide();
        $inputField.prop('disabled', true);
      }

      // Toggle on change
      $checkbox.on('change', function () {
        if ($(this).is(':checked')) {
          $targetDiv.show();
          $inputField.prop('disabled', false);
        } else {
          $targetDiv.hide();
          $inputField.prop('disabled', true);
        }
      });
    });
  });
</script>
@endsection