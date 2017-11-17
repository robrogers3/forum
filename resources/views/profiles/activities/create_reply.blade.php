@component('profiles.activities.activity')

@slot('heading')
<a href="{{$activity->subject->thread->path()}}">
    {{ $activity->subject->thread->title }}
</a>
@endslot

@slot('body')
<div class="body">{{$activity->subject->body}}</div>
@endslot
@slot('footer')
Yep it's a Reply
@endslot    
@endcomponent
