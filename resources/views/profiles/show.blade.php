@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-9">
            <h2>
		<avatar
		    src="{{$profileUser->avatar()}}"
		></avatar>
		{{$profileUser->name}}
	    </h2>
            <small>Since {{$profileUser->created_at->diffForHumans()}}</small>
        </div>
    </div>
    @can('update', $profileUser)    
    <avatar-form
	:user="{{$profileUser}}"
    ></avatar-form>
    @endcan
    

    @can('update', null)
    <h2>mine</h2>
    <set-avatar
	route="{{route('avatar', $profileUser)}}"
	       :user="{{$profileUser}}"
    >
    </set-avatar>
    
    @include('layouts.errors')
    @endcan

    <div class="row">
	<div class="col-md-9">
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
