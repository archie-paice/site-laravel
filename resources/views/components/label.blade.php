<div class='flex flex-col mb-5 w-max'>
    <label class='text-md'>{{ $label }}</label>

    @if (is_null($value) || strlen($value) == 0)
        <input class='input input-md w-100' disabled value="Unassigned" />
    @else
        <input class='input input-md w-100' disabled value="{{ $value }}" />
    @endif
</div>
