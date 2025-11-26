<div>
    @switch($status)
        @case ('active')
            <span class="badge badge-accent">Active</span>
            @break
        @case ('solo')
            <span class="badge badge-secondary">Solo Cert</span>
            @break
        @case ('mock')
            <span class="badge badge-warning">Mock OTS</span>
            @break
        @case ('checkout')
            <span class="badge badge-info">Checkout</span>
            @break
        @case ('complete')
            <span class="badge badge-success">Complete</span>
            @break
        @case ('forfeit')
            <span class="badge badge-error">Forfeit</span>
            @break
        @default
            <span class="badge badge-ghost">Unknown</span>
    @endswitch
</div>

