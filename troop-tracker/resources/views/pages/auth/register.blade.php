@extends('layouts.base')
@section('current_page', 'register')

@section('content')
<x-page-title :title="'Request Access'" />

<div>

  <x-message>
    New to the 501st and/or {{ config('tracker.forum.display_name') }}? Or are you solely a member of another club? Use
    this form below to start signing up for troops. Command Staff will need to approve your account prior to use.
  </x-message>

  <form action="{{ route('register') }}"
        method="POST"
        novalidate="novalidate">
    @csrf

    <x-label>
      First & Last Name (use a nickname if you wish to remain anonymous):
    </x-label>
    <x-input type="text"
             required
             :property="'name'" />
    <br />


    <x-label>
      Email:
    </x-label>
    <x-input type="text"
             required
             :property="'email'" />
    <br />


    <x-label>
      Phone (Optional):
    </x-label>
    <x-input type="text"
             required
             :property="'phone'" />
    <br />


    <x-label>
      Account Type:
    </x-label>
    <x-select :property="'account_type'"
              :options="['1'=>'Regular', '4'=>'Handler']" />
    <br />


    <x-label :value="config('tracker.forum.display_name') . ' Username:'" />
    <x-input type="text"
             required
             autofocus
             :property="'forum_username'" />
    <br />


    <x-label :value="config('tracker.forum.display_name') . ' Password:'" />
    <x-input type="password"
             required
             :property="'forum_password'" />
    <br />

    <p>
      Select your associated clubs below.
    </p>

    @foreach ($clubs as $club)
    @include('pages.auth.partials.club-selection', ['club' => $club])
    <br />
    @endforeach

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