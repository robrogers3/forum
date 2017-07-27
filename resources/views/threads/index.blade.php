@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Threads Forum</div>
                <div class="panel-body">
                    @foreach($threads as $thread)
			<article>
                            <div class="level">
				<h4 class="flex">
                                    <a href="{{$thread->path()}}">
					@if ($thread->hasUpdatesFor(auth()->user()))
					    <strong>{{$thread->title}}<strong>
					@else
						{{$thread->title}}
					@endif
                                    </a>
				</h4>
				<strong>
                                   {{$thread->replies_count}} {{str_plural('reply', $thread->replies_count)}}
				</strong>
                            </div>
                            <h4>in channel <a href="{{$thread->channel->path()}}"> {{$thread->channel->name}} </a>
                            </h4>

                            <div class="body">{{$thread->body}}</div>
			</article>
			<hr>
                    @endforeach
                    {{$threads->links()}}
                </div>
            </div>
        </div>
    </div>
@endsection
