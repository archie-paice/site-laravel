<div class="navbar bg-primary text-primary-content">
    <div class="flex-1">
        <h1>ZJX ARTCC</h1>
    </div>

    <div>
        @if(auth()->user())
            <h1>{{  auth()->user()->first_name }} {{  auth()->user()->last_name }} - {{  auth()->user()->id }}</h1>
        @else
            <h1>Not logged in</h1>
        @endif
    </div>
</div>