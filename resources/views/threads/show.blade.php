@extends('layouts.app')
@section('content')
    <div>
  <thread-view :data="{{$thread}}" inline-template>
    <div class="row">
      <div class="col-md-8">
	  <thread-display
	      :attributes="{{$thread}}"
	      :channels="{{$channels}}"
	      @can('update', $thread)
	      :updatable="true"
	      @endcan
	     ></thread-display>
	<replies :thread="{{$thread}}" @added="repliesCount++" @removed="repliesCount--"></replies>
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
		<button class="btn btn-default" style="margin-top: 10px;margin-left: 10px;" v-if="authorize('lockThread')" @click="toggleLock" v-text='locked ? "Unlock" : "Lock"'>Lock</button>
	    @endif
	  </div>
	</div>"
      </div>
    </div>
  </thread-view>
</div>
@endsection
