@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="page-header">
            <h2>{{$profileUser->name}}</h2>
            <small>Since {{$profileUser->created_at->diffForHumans()}}</small>
        </div>
        <div class="row">
            @foreach($activities as $activity)
                include('profiles.{{$activity->type}}')
            @endforeach
            {{$activities->links()}}
        </div>
    </div>
</div>
@endsection
