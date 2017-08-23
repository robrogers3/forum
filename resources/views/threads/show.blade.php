@extends('layouts.app')
@section('content')
    <div>
	<thread-view :data="{{$thread}}" inline-template>
	    <div class="row">
		<div class="col-md-8">
		    <div class="panel panel-info">
			<div class="panel-heading">
			    <div class="level btw">
				<avatar
				    class="mr-1"
				    src="{{$thread->creator->avatar()}}"
				></avatar>
				<h4 class="flex">{{$thread->title}} </h4>

				@can ('update', $thread)
				<form method="POST" action="{{$thread->path()}}">
				    {{csrf_field()}}
				    {{method_field('delete')}}
				    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
				</form>
				@endcan
				
			    </div>
			    <div>Posted By {{$thread->creator->name}} {{$thread->created_at->diffForHumans()}}</div>



			</div>
			<div class="panel-body">
			    <div class="body">{{$thread->body}}</div>
			</div>
		    </div>
		    <replies @added="repliesCount++" @removed="repliesCount--"></replies>
		</div>
		<div class="col-md-4">
		    <div class="panel panel-info">
			<div class="panel-body">
			    <div class="body">
				This thread was published  {{$thread->created_at->diffForHumans()}}
				by <a href="/profiles/{{$thread->creator->name}}">{{$thread->creator->name}}</a>
				and currently has 
				<span v-text="repliesCount">NOPE</span>
				{{str_plural('comment', $thread->replies_count)}}.
			    </div>
			    @if(auth()->check())
				<subscribe-button
				    :subscribed="subscribed"
				    v-on:togglesubscription="toggleSubscription"
				>
				</subscribe-button>
			    @endif
			</div>
		    </div>"
		</div>
	    </div>
	</thread-view>
    </div>
@endsection
