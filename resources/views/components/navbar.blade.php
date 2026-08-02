@php
    $publicationCategories = \App\Models\PublicationCategory::forNavbar();
    $mobilePublicationCategories = \App\Models\PublicationCategory::forMobileNav();
@endphp

<div class="navbar sticky top-0 bg-primary text-primary-content z-20 px-3 sm:px-5">
    {{-- Logo + Home --}}
    <div class="flex-1 min-w-0">
        <a href='{{ route('home') }}' class='flex items-center gap-2 shrink-0' title="Home">
            <img src="{{ asset('images/zjx_wide.png') }}" alt="ZJX ARTCC" class="h-8 sm:h-9 w-auto" />
        </a>
    </div>

    {{-- Desktop nav (hidden on mobile) --}}
    <ul class='hidden md:flex menu menu-horizontal items-center gap-x-4'>
        <li><a href="{{ route('home') }}" class="font-medium">Home</a></li>

        <div class="dropdown">
            <div tabindex="0" role="button" class="m-1 flex items-center gap-2">
                <span>Events</span>
                <x-dropdown-icon/>
            </div>
            <ul tabindex="-1" class="dropdown-content text-base-content menu bg-base-100 rounded-box z-50 w-52 p-2 shadow-sm">
                <li><a href="{{ route('events.index') }}">Upcoming Events</a></li>
            </ul>
        </div>

        <div class="dropdown">
            <div tabindex="0" role="button" class="m-1 flex items-center gap-2">
                <span>Controllers</span>
                <x-dropdown-icon/>
            </div>
            <ul tabindex="-1" class="dropdown-content text-base-content menu bg-base-100 rounded-box z-50 w-56 p-2 shadow-sm">
                <li><a href="{{ route('visit.index') }}">Visit vZJX</a></li>
                <li><a href="{{ route('roster.index') }}">Roster</a></li>
                <li><a href="{{ route('staff.index') }}">Facility Staff</a></li>
                <li><a href="{{ route('statistics.index') }}">Statistics</a></li>
            </ul>
        </div>

        <div class="dropdown">
            <div tabindex="0" role="button" class="m-1 flex items-center gap-2">
                <span>Publications</span>
                <x-dropdown-icon/>
            </div>
            <ul tabindex="-1" class="dropdown-content text-base-content menu bg-base-100 rounded-box z-50 w-64 p-2 shadow-sm">
                <li><a href="{{ route('publications.index') }}">All Documents</a></li>
                @if($publicationCategories->isNotEmpty())
                    <li class="menu-title text-xs uppercase tracking-wide pt-2">Categories</li>
                    @foreach($publicationCategories as $publicationCategory)
                        <li><a href="{{ route('publications.index') }}#category-{{ $publicationCategory->id }}">{{ $publicationCategory->title }}</a></li>
                    @endforeach
                @endif
            </ul>
        </div>

        @hasrole('staff')
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="m-1 flex items-center gap-2">
                    <span>Facility Admin</span>
                    <x-dropdown-icon/>
                </div>
                <ul tabindex="-1" class="dropdown-content text-base-content menu bg-base-100 rounded-box z-50 w-52 p-2 shadow-sm">
                    <li><a href="{{ route('admin.index') }}">Dashboard</a></li>

                    @hasrole('training')
                        <li><a href={{ route('admin.index') }}>Training Management</a></li>
                    @endhasrole

                    @hasrole('facilities')
                        <li><a href={{ route('admin.index') }}>Data Management</a></li>
                    @endhasrole

                    @hasrole('events')
                        <li><a href={{ route('admin.index') }}>Events Management</a></li>
                    @endhasrole

                    @hasrole('admin')
                        <li><a href={{ route('admin.index') }}>Admin</a></li>
                    @endhasrole
                </ul>
            </div>
        @endhasrole

        @if(auth()->user())
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="m-1 flex items-center gap-2">
                    <span>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }} - {{ auth()->user()->id }}</span>
                    <x-dropdown-icon/>
                </div>
                <ul tabindex="-1" class="dropdown-content text-base-content menu bg-base-100 rounded-box z-50 w-52 p-2 shadow-sm">
                    <li><a href="{{ route('users.show', [auth()->user()->id]) }}">Profile</a></li>
                    <li><a href="{{ route('auth.logout') }}">Logout</a></li>
                </ul>
            </div>
        @else
            <x-login-button />
        @endif

        <li>
            <label class="swap swap-rotate btn btn-ghost btn-circle" aria-label="Toggle dark mode">
                <input type="checkbox"
                    :checked="theme === 'dark' || (!theme && matchMedia('(prefers-color-scheme: dark)').matches)"
                    @change="theme = $event.target.checked ? 'dark' : 'light'" />
                <svg class="swap-off h-5 w-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M5.64,17l-.71.71a1,1,0,0,0,0,1.41,1,1,0,0,0,1.41,0l.71-.71A1,1,0,0,0,5.64,17ZM5,12a1,1,0,0,0-1-1H3a1,1,0,0,0,0,2H4A1,1,0,0,0,5,12Zm7-7a1,1,0,0,0,1-1V3a1,1,0,0,0-2,0V4A1,1,0,0,0,12,5ZM5.64,7.05a1,1,0,0,0,.7.29,1,1,0,0,0,.71-.29,1,1,0,0,0,0-1.41l-.71-.71A1,1,0,0,0,4.93,6.34Zm12,.29a1,1,0,0,0,.7-.29l.71-.71a1,1,0,1,0-1.41-1.41L17,5.64a1,1,0,0,0,0,1.41A1,1,0,0,0,17.66,7.34ZM21,11H20a1,1,0,0,0,0,2h1a1,1,0,0,0,0-2Zm-9,8a1,1,0,0,0-1,1v1a1,1,0,0,0,2,0V20A1,1,0,0,0,12,19ZM18.36,17A1,1,0,0,0,17,18.36l.71.71a1,1,0,0,0,1.41,0,1,1,0,0,0,0-1.41ZM12,6.5A5.5,5.5,0,1,0,17.5,12,5.51,5.51,0,0,0,12,6.5Zm0,9A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z"/></svg>
                <svg class="swap-on h-5 w-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22v.27A10.15,10.15,0,0,0,17.22,15.63a9.79,9.79,0,0,0,2.1-.22A8.11,8.11,0,0,1,12.14,19.73Z"/></svg>
            </label>
        </li>
    </ul>

    {{-- Mobile hamburger (visible on mobile only) --}}
    <div class="md:hidden dropdown dropdown-end">
        <button tabindex="0" class="btn btn-ghost btn-sm px-2" aria-label="Menu">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
        </button>
        <ul tabindex="-1" class="dropdown-content text-base-content menu bg-base-100 rounded-box z-50 w-56 p-3 shadow-lg mt-2 space-y-0.5">
            <li><a href="{{ route('home') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1" /></svg>
                Home
            </a></li>

            <li class="menu-title text-xs uppercase tracking-wide pt-2">Events</li>
            <li><a href="{{ route('events.index') }}">Upcoming Events</a></li>

            <li class="menu-title text-xs uppercase tracking-wide pt-2">Controllers</li>
            <li><a href="{{ route('visit.index') }}">Visit vZJX</a></li>
            <li><a href="{{ route('roster.index') }}">Roster</a></li>
            <li><a href="{{ route('staff.index') }}">Facility Staff</a></li>
            <li><a href="{{ route('statistics.index') }}">Statistics</a></li>

            <li class="menu-title text-xs uppercase tracking-wide pt-2">Publications</li>
            <li><a href="{{ route('publications.index') }}">All Documents</a></li>
            @foreach($mobilePublicationCategories as $publicationCategory)
                <li><a href="{{ route('publications.index') }}#category-{{ $publicationCategory->id }}">{{ $publicationCategory->title }}</a></li>
            @endforeach

            @hasrole('staff')
                <li class="menu-title text-xs uppercase tracking-wide pt-2">Facility Admin</li>
                <li><a href="{{ route('admin.index') }}">Dashboard</a></li>
                @hasrole('training')
                    <li><a href={{ route('admin.index') }}>Training Management</a></li>
                @endhasrole
                @hasrole('facilities')
                    <li><a href={{ route('admin.index') }}>Data Management</a></li>
                @endhasrole
                @hasrole('events')
                    <li><a href={{ route('admin.index') }}>Events Management</a></li>
                @endhasrole
                @hasrole('admin')
                    <li><a href={{ route('admin.index') }}>Admin</a></li>
                @endhasrole
            @endhasrole

            <div class="divider my-1"></div>

            @if(auth()->user())
                <li class="menu-title text-xs uppercase tracking-wide pt-2">Profile</li>
                <li><a href="{{ route('users.show', [auth()->user()->id]) }}">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }} - {{ auth()->user()->rating->mapToString() }}</a></li>
                <li><a href="{{ route('auth.logout') }}" class="text-error">Logout</a></li>
            @else
                <li><a href="#" onclick="event.preventDefault(); vatsim_login_modal.showModal()">Login With VATSIM</a></li>
            @endif

            <div class="divider my-1"></div>

            <li>
                <label class="flex items-center justify-between cursor-pointer">
                    <span>Dark Mode</span>
                    <input type="checkbox" class="toggle toggle-sm"
                        :checked="theme === 'dark' || (!theme && matchMedia('(prefers-color-scheme: dark)').matches)"
                        @change="theme = $event.target.checked ? 'dark' : 'light'" />
                </label>
            </li>
        </ul>
    </div>
</div>

{{-- VATSIM login confirmation modal (shared by desktop & mobile triggers) --}}
@guest
<dialog id="vatsim_login_modal" class="modal" x-data="{ confirmed: false }" @close="confirmed = false">
    <div class="modal-box text-base-content">
        <h3 class="font-bold text-lg">Confirm Sign In</h3>
        <p class="py-4 text-sm">
            The information contained on all pages of this website is to be used for flight simulation purposes only on the VATSIM network. It is not intended nor should it be used for real world navigation. This site is not affiliated with the FAA, NATCA, the actual Jacksonville ARTCC, or any governing aviation body. All content contained herein is approved only for use on the VATSIM network.
        </p>
        <label class="flex items-start cursor-pointer gap-3 mt-2">
            <input type="checkbox" x-model="confirmed" class="checkbox checkbox-primary mt-0.5 shrink-0" />
            <span class="text-sm leading-snug">I understand that <strong>we are a virtual organization</strong> and do NOT have any affiliation with the FAA, ZJX, or any government agency.</span>
        </label>
        <div class="modal-action">
            <form method="dialog">
                <button class="btn btn-error">Cancel</button>
            </form>
            <button type="button"
                    x-bind:disabled="!confirmed"
                    x-bind:class="confirmed ? 'btn btn-success' : 'btn btn-success btn-disabled'"
                    @click="window.location.href='{{ route('auth.redirect') }}'">
                Continue with Login
            </button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
@endguest
