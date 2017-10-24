@component('profiles.activities.activity')

@slot('heading')

{{$profileUser->name}} favorited a reply on thread
<a href="{{$activity->subject->favorited->path()}}"> {{$activity->subject->favorited->thread->title}}</a>

@endslot
    
@slot('body')
    {{ $activity->subject->favorited->body }}
@endslot

@slot('footer')
yeah favorite
@endslot
@endcomponent
