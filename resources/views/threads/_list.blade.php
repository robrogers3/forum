<div class="panel panel-default">
    <div class="panel-heading">
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
    <h5>
	Posted by <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a> in channel <a href="{{$thread->channel->path()}}"> {{$thread->channel->name}} </a>
    </h5>
    </div>
    <div class="panel-body">
	<div class="body">{{$thread->body}}</div>
    </div>
    <div class="panel-footer">
        {{ $thread->visits()->count() }} Visits
    </div>
</div>
<hr>
