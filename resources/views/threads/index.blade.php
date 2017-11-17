@extends('layouts.app')
@section('content')
    <h1>session {{Session::get('foo')}}</h1>
    <div class="row">
        <div class="col-md-8">
	    @include('threads._list')
	    {{$threads->links()}}
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
