@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">Threads Forum!!</div>
                <div class="panel-body">
                    @foreach($threads as $thread)
			@include('threads._list')
                    @endforeach
                    {{$threads->links()}}
                </div>
            </div>
	</div>
	<div class="col-md-4">
	    @if (count($trending))
		<div class="panel panel-info">
		    <div class="panel-heading">
			Trending Threads
		    </div>
		    <div class="panel-body">
			<ul class="list-group">
			    @foreach($trending as $trending)
			    <li class="list-group-item">
				<a href="{{url($trending->path)}}">{{$trending->title}}</a>
			    </li>
			    @endforeach
			</ul>
		    </div>
		</div>
	    @endif
	</div>
    </div>
@endsection
