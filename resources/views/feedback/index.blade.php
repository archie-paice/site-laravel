@extends('layouts.main')

@section('title', 'Feedback')

@section('body')
    <div class="max-w-2xl">
        <x-card-component title="Submit Feedback">
            <p class="text-lg mb-4">Had a session with one of our controllers? Let us know how it went.</p>

            <form action="{{ route('feedback.store') }}" method="POST" class="flex flex-col gap-y-4">
                @csrf

                {{-- Autofilled submitter info --}}
                <div class="grid md:grid-cols-3 grid-cols-1 gap-4">
                    <label class="form-control flex flex-col">
                        <span class="label-text font-semibold mb-1">Your Name</span>
                        <input type="text" class="input input-bordered w-full bg-base-300 text-base-content/60" value="{{ auth()->user()->name }}" disabled>
                    </label>

                    <label class="form-control flex flex-col">
                        <span class="label-text font-semibold mb-1">CID</span>
                        <input type="text" class="input input-bordered w-full bg-base-300 text-base-content/60" value="{{ auth()->user()->id }}" disabled>
                    </label>

                    <label class="form-control flex flex-col">
                        <span class="label-text font-semibold mb-1">Email</span>
                        <input type="email" class="input input-bordered w-full bg-base-300 text-base-content/60" value="{{ auth()->user()->email }}" disabled>
                    </label>
                </div>

                <div class="grid md:grid-cols-2 grid-cols-1 gap-4">
                    <label class="form-control flex flex-col">
                        <span class="label-text font-semibold mb-1">Controller</span>
                        <select name="controller_id" class="select select-bordered w-full" required>
                            <option value="" disabled @selected(old('controller_id') === null)>Select a controller</option>
                            @foreach ($controllers as $controller)
                                <option value="{{ $controller->id }}" @selected(old('controller_id') == $controller->id)>
                                    {{ $controller->name_reversed }} - {{ $controller->id }}
                                </option>
                            @endforeach
                        </select>
                    </label>

                    <label class="form-control flex flex-col">
                        <span class="label-text font-semibold mb-1">Position</span>
                        <input type="text" name="position" class="input input-bordered w-full" placeholder="e.g. JAX_APP"
                               value="{{ old('position') }}" required>
                    </label>
                </div>

                <label class="form-control flex flex-col">
                    <span class="label-text font-semibold mb-1">How was your experience?</span>
                    <select name="experience" class="select select-bordered w-full" required>
                        <option value="" disabled @selected(old('experience') === null)>Select a rating</option>
                        @foreach ($experiences as $experience)
                            <option value="{{ $experience->value }}" @selected(old('experience') === $experience->value)>{{ $experience->value }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="form-control flex flex-col">
                    <span class="label-text font-semibold mb-1">Comments / Feedback</span>
                    <textarea name="comments" rows="5" class="textarea textarea-bordered w-full resize-none"
                              placeholder="Tell us about your experience" required>{{ old('comments') }}</textarea>
                </label>

                <label class="flex items-center gap-x-3 cursor-pointer w-max">
                    <input type="checkbox" name="staff_followup" value="1" class="checkbox" @checked(old('staff_followup'))>
                    <span>I would like a staff member to follow up with me</span>
                </label>

                <button type="submit" class="btn btn-primary w-max">Submit Feedback</button>
            </form>
        </x-card-component>
    </div>
@endsection
