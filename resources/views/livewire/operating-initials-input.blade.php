@php
    use \App\Enums\VisitRequestStatus;
@endphp

<div class='flex flex-col'>
    <label for="operatingInitials" class='label'>Operating Initials (if applicable)</label>
    <input 
        @disabled($this->visitRequest->status !== VisitRequestStatus::PENDING)
        class='input'
        type="text"
        name="operatingInitials"
        id="operatingInitials"
        maxlength='2'
        wire:model.debounce.300ms="operatingInitials"
        value='{{ old('operatingInitials', $operatingInitials) }}'>

    @error('operatingInitials')
        <span class="text-error text-sm">{{ $message }}</span>
    @enderror
</div>
