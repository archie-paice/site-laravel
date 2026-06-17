<div class="navbar sticky top-0 bg-primary text-primary-content z-20">
  <div class="navbar-start">
    <div class="dropdown">
      <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" /> </svg>
      </div>
      <ul
        tabindex="-1"
        class="menu menu-sm dropdown-content bg-base-100 text-base-content rounded-box z-1 mt-3 w-52 p-2 shadow">
        <x-nav-links/>
      </ul>
    </div>
    <a href="{{ route('home') }}">
        <img src="{{ asset('images/zjx_wide.png') }}"/>
    </a>
  </div>
  <div class="navbar-end hidden lg:flex">
    <ul class="menu menu-horizontal items-center px-1 gap-x-5">
        <x-nav-links/>
    </ul>
  </div>
</div>