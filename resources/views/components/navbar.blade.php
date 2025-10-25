<div class="navbar sticky bg-primary text-primary-content z-10">
    <div class="flex-1">
        <a href='{{ route('home') }}' class='font-bold text-2xl'>ZJX ARTCC</a>
    </div>

    <div>
        @if(auth()->user())
            <h2>{{  auth()->user()->first_name }} {{  auth()->user()->last_name }} - {{  auth()->user()->id }}</h2>
        @else
            <x-login-button />
        @endif
    </div>
</div>