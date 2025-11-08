@extends('layouts.main')

@section('title', 'Profile - '.$user->first_name.' '.$user->last_name)

@section('body')
    <x-user-data :user='$user'/>
@endsection
