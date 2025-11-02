@extends('layouts.main')

@section('body')
    <div class='card w-max bg-base-300 p-5'>
        <x-user-data :user='$user'/>
    </div>
@endsection