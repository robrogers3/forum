@component('profiles.activities.activity')

@slot('heading')
{{$profileUser->name}} replied to 
<a href="{$activity->subject->path()}}">
    {{ $activity->subject->title }}
</a>

<div class="level">
    <h4 class="flex">Published in channel
	<a href="{{$activity->subject->channel->path()}}"> {{$activity->subject->channel->name}} </a>
    </h4>
    <h5>{{$activity->subject->created_at->diffForHumans()}}</h5>
</div>
@endslot

@slot('body')
    {{$activity->subject->body}}
@endslot

@endcomponent
