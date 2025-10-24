<div class="topnav"
     id="myTopnav">
  <a href="{{ url('/') }}"
     class="{{ $current_page == 'home' ? 'active' : '' }}">Home</a>
  <a href="{{ config('forum.url') }}"
     class="{{ $current_page == 'forums' ? 'active' : '' }}">Forums</a>
  <a href="{{ url('index.php?action=requestaccess') }}"
     class="{{ $current_page == 'request' ? 'active' : '' }}">Request Access</a>
  <a href="{{ url('index.php?action=setup') }}"
     class="{{ $current_page == 'setup' ? 'active' : '' }}">Account Setup</a>
  <a href="{{ route('faq') }}"
     class="{{ $current_page == 'faq' ? 'active' : '' }}">FAQ</a>
  @auth
  <a href="{{ route('logout') }}"
     class="{{ $current_page == 'login' ? 'active' : '' }}">Logout</a>
  @else
  <a href="{{ route('login') }}"
     class="{{ $current_page == 'login' ? 'active' : '' }}">Login</a>
  @endauth
  <a href="javascript:void(0);"
     class="icon"
     onclick="myFunction()"><i class="fa fa-bars"></i></a>
</div>