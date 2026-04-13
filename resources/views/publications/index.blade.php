@extends('layouts.main')

@section('title', 'Publications & Downloads')

@section('body')
    <div class="w-full px-4 sm:px-6 lg:px-8">

        {{-- Intro --}}
        <p class="text-base-content/70 mb-6 max-w-3xl">
            Official vZJX documents including standard operating procedures, letters of agreement, training materials, quick reference guides, and facility maps. All documents are for simulation use only.
        </p>

        {{-- Category tabs (mobile: scroll horizontally; desktop: inline) --}}
        <div class="flex gap-2 flex-wrap mb-6">
            @foreach($categories as $category)
                <a href="#{{ $category['slug'] }}"
                   class="btn btn-sm btn-outline">
                    {{ $category['title'] }}
                </a>
            @endforeach
        </div>

        {{-- Categories --}}
        <div class="flex flex-col gap-8">
            @foreach($categories as $category)
                <section id="{{ $category['slug'] }}" class="scroll-mt-20">
                    <x-card-component>
                        {{-- Category header --}}
                        <div class="flex items-start gap-3 mb-4">
                            <div class="p-2 rounded-lg bg-primary/10 text-primary shrink-0">
                                @if($category['icon'] === 'document-text')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                @elseif($category['icon'] === 'document-duplicate')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                @elseif($category['icon'] === 'academic-cap')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                    </svg>
                                @elseif($category['icon'] === 'bookmark')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                    </svg>
                                @elseif($category['icon'] === 'map')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <h2 class="card-title text-xl sm:text-2xl">{{ $category['title'] }}</h2>
                                <p class="text-base-content/60 text-sm mt-0.5">{{ $category['description'] }}</p>
                            </div>
                        </div>

                        {{-- Documents list --}}
                        <div class="flex flex-col divide-y divide-base-200">
                            @foreach($category['documents'] as $doc)
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 py-3 first:pt-0 last:pb-0">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="font-medium text-base">{{ $doc['name'] }}</span>
                                            <span class="badge badge-outline badge-sm">{{ $doc['version'] }}</span>
                                        </div>
                                        <p class="text-sm text-base-content/60 mt-0.5">{{ $doc['description'] }}</p>
                                        <p class="text-xs text-base-content/40 mt-0.5">
                                            Updated {{ \Carbon\Carbon::parse($doc['updated_at'])->utc()->format('D, d M Y H:i:s') }} GMT
                                        </p>
                                    </div>
                                    <div class="shrink-0 flex gap-2">
                                        <a href="{{ $doc['file_url'] }}"
                                           target="_blank"
                                           class="btn btn-outline btn-sm gap-1.5"
                                           aria-label="View {{ $doc['name'] }} in browser">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View
                                        </a>
                                        <a href="{{ $doc['file_url'] }}"
                                           download
                                           class="btn btn-primary btn-sm gap-1.5"
                                           aria-label="Download {{ $doc['name'] }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Download
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-card-component>
                </section>
            @endforeach
        </div>

    </div>
@endsection
