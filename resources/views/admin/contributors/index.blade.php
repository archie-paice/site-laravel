@extends('layouts.admin')

@section('title', 'Manage Contributors')

@section('body')
    <div class="w-full px-4 sm:px-6 lg:px-8 flex flex-col gap-6">

        <x-card-component title="Add Manual Contributor">
            <form method="POST" action="{{ route('admin.contributors.store') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mt-4">
                @csrf
                <div class="flex flex-col gap-1">
                    <label class="label label-text text-sm">GitHub Username <span class="text-base-content/50">(optional)</span></label>
                    <input type="text" name="github_username" placeholder="GitHub username"
                           value="{{ old('github_username') }}"
                           class="input input-bordered @error('github_username') input-error @enderror">
                    @error('github_username')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
                <div class="flex flex-col gap-1">
                    <label class="label label-text text-sm">Display Name <span class="text-base-content/50">(required if no GitHub username)</span></label>
                    <input type="text" name="display_name" placeholder="Real name"
                           value="{{ old('display_name') }}"
                           class="input input-bordered @error('display_name') input-error @enderror">
                    @error('display_name')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
                <div class="flex flex-col gap-1">
                    <label class="label label-text text-sm">Section</label>
                    <select name="section" class="select select-bordered">
                        <option value="main"        {{ old('section') === 'main'        ? 'selected' : '' }}>Main Contributors</option>
                        <option value="fork"        {{ old('section', 'fork') === 'fork' ? 'selected' : '' }}>Fork Contributors</option>
                        <option value="contributor" {{ old('section') === 'contributor' ? 'selected' : '' }}>Contributors</option>
                        <option value="beta"        {{ old('section') === 'beta'        ? 'selected' : '' }}>Beta Testers</option>
                    </select>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="label label-text text-sm">Note <span class="text-base-content/50">(optional)</span></label>
                    <input type="text" name="note" placeholder="e.g. Fork contributor"
                           value="{{ old('note') }}"
                           class="input input-bordered">
                </div>
                <div class="sm:col-span-2 lg:col-span-4">
                    <button type="submit" class="btn btn-primary">Add Contributor</button>
                </div>
            </form>
        </x-card-component>

        <x-card-component title="Manual Contributors">
            @if($contributors->isEmpty())
                <p class="mt-4 text-base-content/70">No manual contributors added yet.</p>
            @else
                <div class="overflow-x-auto mt-4">
                    <table class="table w-full">
                        <thead>
                            <tr>
                                <th>GitHub Username</th>
                                <th>Display Name</th>
                                <th>Section</th>
                                <th>Note</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contributors as $contributor)
                                <tr>
                                    <td>
                                        @if($contributor->github_username)
                                            <a href="https://github.com/{{ $contributor->github_username }}" target="_blank" class="link">
                                                {{ $contributor->github_username }}
                                            </a>
                                        @else
                                            <span class="text-base-content/40">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $contributor->display_name ?? '—' }}</td>
                                    <td>
                                        @php
                                            $sectionLabels = ['main' => 'Main', 'fork' => 'Fork', 'contributor' => 'Contributor', 'beta' => 'Beta Tester'];
                                            $sectionStyles = ['main' => 'badge-primary', 'fork' => 'badge-secondary', 'contributor' => 'badge-accent', 'beta' => 'badge-info'];
                                        @endphp
                                        <span class="badge {{ $sectionStyles[$contributor->section] ?? 'badge-ghost' }}">
                                            {{ $sectionLabels[$contributor->section] ?? $contributor->section }}
                                        </span>
                                    </td>
                                    <td>{{ $contributor->note ?? '—' }}</td>
                                    <td class="text-right">
                                        <form method="POST" action="{{ route('admin.contributors.destroy', $contributor) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-error">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-card-component>

    </div>
@endsection
