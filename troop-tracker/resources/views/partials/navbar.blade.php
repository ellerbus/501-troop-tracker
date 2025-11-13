<nav class="navbar navbar-dark navbar-expand-lg bg-black rounded-3 p-0">
  <div class="container-fluid justify-content-center">
    <!-- Hamburger toggle -->
    <button class="navbar-toggler ms-auto me-3 my-2"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#pillNav"
            aria-controls="pillNav"
            aria-expanded="false"
            aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>


    <div class="collapse navbar-collapse justify-content-center"
         id="pillNav">
      <ul class="navbar-nav flex-wrap">
        <x-nav-link :href="'#'"
                    :active="request()->routeIs('home')">
          Home
        </x-nav-link>
        <x-nav-link :href="config('forum.url')">
          Forum
        </x-nav-link>
        <x-nav-link :href="route('faq')"
                    :active="request()->routeIs('faq')">
          FAQ
        </x-nav-link>

        @auth
        <x-nav-link :href="route('account.display')"
                    :active="request()->routeIs('account.display')">
          Manage Account
        </x-nav-link>
        <x-nav-link :href="route('auth.logout')">
          Logout
        </x-nav-link>
        @else
        <x-nav-link :href="route('auth.register')"
                    :active="request()->routeIs('auth.register')">
          Request Access
        </x-nav-link>
        <x-nav-link :href="'#'"
                    :active="request()->routeIs('x')">
          Account Setup
        </x-nav-link>
        <x-nav-link :href="route('auth.login')"
                    :active="request()->routeIs('auth.login')">
          Login
        </x-nav-link>
        @endauth
      </ul>
    </div>
  </div>
</nav>