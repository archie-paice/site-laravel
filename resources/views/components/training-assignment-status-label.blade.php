<div>
    @switch($status)
        @case (\App\Enums\TrainingStatus::ACTIVE)
            <span class="badge badge-accent text-accent-content">Active</span>
            @break
        @case (\App\Enums\TrainingStatus::SOLO)
            <span class="badge badge-secondary text-secondary-content">Solo Cert</span>
            @break
        @case (\App\Enums\TrainingStatus::MOCK)
            <span class="badge badge-warning text-warning-content">Mock OTS</span>
            @break
        @case (\App\Enums\TrainingStatus::CHECKOUT)
            <span class="badge badge-info text-info-content">Checkout</span>
            @break
        @case (\App\Enums\TrainingStatus::COMPLETE)
            <span class="badge badge-success text-success-content">Complete</span>
            @break
        @case (\App\Enums\TrainingStatus::FORFEIT)
            <span class="badge badge-error text-error-content">Forfeit</span>
            @break
        @default
            <span class="badge badge-ghost">Unknown</span>
    @endswitch
</div>

