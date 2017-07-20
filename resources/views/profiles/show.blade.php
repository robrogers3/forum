@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-xs-12" style="background:pink;">
            <h2>{{$profileUser->name}}</h2>
            <small>Since {{$profileUser->created_at->diffForHumans()}}</small>
        </div>
    </div>
    <div class="row">
	<div class="col-md-12">
	@foreach($activities as $date => $activity)
	    <h3 class="page-header">{{$date}}</h3>
	    @foreach($activity as $record)
		@if (view()->exists("profiles.activities.{$record->type}"))
		    @include("profiles.activities.$record->type", ['activity' => $record])
		@endif
	    @endforeach
	@endforeach
        {$activities->links()}
	</div>
    </div>
@endsection
