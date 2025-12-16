@extends('layouts.profile')

@section('profile-content')
    <x-training-assignments-profile-table :userId="$user->id" :trainingAssignments="$trainingAssignments"/>
@endsection