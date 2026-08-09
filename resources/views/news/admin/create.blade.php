@extends('layouts.admin')

@section('title', 'Create News Announcement')

@section('body')
    <div class=" mx-auto">
    <x-card-component class="">
        <div class="flex flex-col ">
            <h1 class="font-bold text-xl mb-5">
                Announcement Information
            </h1>
            <form method="POST" class="flex flex-col gap-5" action="{{route('admin.news.store')}}">
                @csrf

                <div>
                    <label for="title" class="label font-bold text-black">Title</label>
                    <input
                        type="text"
                        name="title"
                        class="input w-full"
                        value="{{old('title')}}"
                        required
                    >
                </div>

                <div>
                    <label for="content" class="label font-bold text-black">Content</label>
                    <textarea class="textarea bg-base-100 text-base-content min-h-50 w-full" name="content"> {{ old('content') }}</textarea>
                </div>

                <div class="card-actions mt-5">
                    <button
                        class="btn btn-primary"
                        type="submit"
                    >Publish Announcement <i class="fa-regular fa-paper-plane"></i></button>
                </div>

            </form>
        </div>

    </x-card-component>
    </div>
@endsection
