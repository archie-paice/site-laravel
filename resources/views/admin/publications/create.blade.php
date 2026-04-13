@extends('layouts.admin')

@section('title', 'Add Document')

@section('body')
    <div class="w-full px-4 sm:px-6 lg:px-8 max-w-3xl">

        <a href="{{ route('admin.publications.index') }}" class="btn btn-ghost btn-sm mb-4 gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Documents
        </a>

        <form method="POST" action="{{ route('admin.publications.store') }}" class="flex flex-col gap-5">
            @csrf

            {{-- Document Details --}}
            <div class="collapse collapse-open bg-base-100 border border-base-300">
                <input type="checkbox" checked />
                <div class="collapse-title font-semibold">Document Details</div>
                <div class="collapse-content flex flex-col gap-4">

                    <div>
                        <label for="name" class="label">Document Name <span class="text-error">*</span></label>
                        <input id="name"
                               name="name"
                               type="text"
                               required
                               placeholder="e.g. ZJX ARTCC SOP"
                               value="{{ old('name') }}"
                               class="input input-bordered w-full @error('name') input-error @enderror" />
                        @error('name')
                            <p class="text-error text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="label">Description <span class="text-error">*</span></label>
                        <textarea id="description"
                                  name="description"
                                  required
                                  rows="3"
                                  placeholder="Brief description of this document..."
                                  class="textarea textarea-bordered w-full @error('description') textarea-error @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-error text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Category & Version --}}
            <div class="collapse collapse-open bg-base-100 border border-base-300">
                <input type="checkbox" checked />
                <div class="collapse-title font-semibold">Category & Version</div>
                <div class="collapse-content flex flex-col gap-4">

                    <div>
                        <label for="category" class="label">Category <span class="text-error">*</span></label>
                        <select id="category"
                                name="category"
                                required
                                class="select select-bordered w-full @error('category') select-error @enderror">
                            <option disabled {{ old('category') ? '' : 'selected' }}>Select a category</option>
                            @foreach($categories as $slug => $label)
                                <option value="{{ $slug }}" {{ old('category') === $slug ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="text-error text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="version" class="label">Version <span class="text-error">*</span></label>
                        <input id="version"
                               name="version"
                               type="text"
                               required
                               placeholder="e.g. v1.0"
                               value="{{ old('version') }}"
                               class="input input-bordered w-full @error('version') input-error @enderror" />
                        @error('version')
                            <p class="text-error text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <p class="text-xs text-base-content/50 mt-1">
                        The "Updated At" timestamp will be set automatically when the document is saved.
                    </p>

                </div>
            </div>

            {{-- File URL --}}
            <div class="collapse collapse-open bg-base-100 border border-base-300">
                <input type="checkbox" checked />
                <div class="collapse-title font-semibold">File URL</div>
                <div class="collapse-content flex flex-col gap-4">

                    <div>
                        <label for="file_url" class="label">Document URL <span class="text-error">*</span></label>
                        <input id="file_url"
                               name="file_url"
                               type="url"
                               required
                               placeholder="https://..."
                               value="{{ old('file_url') }}"
                               class="input input-bordered w-full @error('file_url') input-error @enderror" />
                        <p class="text-xs text-base-content/50 mt-1">Direct link to the PDF or document file. Must be a publicly accessible URL.</p>
                        @error('file_url')
                            <p class="text-error text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn btn-primary">Add Document</button>
                <a href="{{ route('admin.publications.index') }}" class="btn btn-ghost">Cancel</a>
            </div>

        </form>

    </div>
@endsection
