@extends('layouts.base')
@section('current_page', 'login')

@section('content')

<h2 class="tm-section-header">Login</h2>
<form method="POST"
      action="{{ route('login') }}">
  @csrf

  <x-label :value="config('forum.name') . ' Username:'" />
  <x-input type="text"
           name="username"
           id="username"
           value="{{ old('username') }}"
           required
           autofocus />
  <x-input-error :property="'username'" />

  <x-label :value="config('forum.name') . ' Password:'" />
  <x-input type="password"
           name="password"
           id="password"
           value="{{ old('password') }}"
           required
           autofocus />
  <x-input-error :property="'password'" />

  <br /><br />

  <x-input type="checkbox"
           name="remember_me"
           value="Y" /> Keep me logged in

  <br /><br />

  <x-submit-button>
    Login
  </x-submit-button>
</form>

<p>
  <small>
    <b>Remember:</b><br />Login with your {{ config('forum.name') }} board username and password.
  </small>
</p>

@endsection