<reply :data="{{$reply}}" inline-template v-cloak>
    <div id="reply-{{$reply->id}}" class="panel panel-default">
	<div class="panel-heading">
            <div class="level">
		<h5 class="flex">
		    <a href="/profiles/{{$reply->owner->name}}">{{$reply->owner->name}}</a>  said  {{$reply->created_at->diffForHumans()}}</h5>
		@if(auth()->check())
		<favorite :reply="{{$reply}}"></favorite>
		@endif
            </div>
	</div>
	<div class="panel-body">
	  <div v-show="editing">
	       <div class="form-group">
		 <textarea class="form-control" v-model="body"></textarea>
	       </div>
	       <div>
		 <button class="btn btn-xs btn-primary mr-1" @click="update">Update</button>
		 <button class="btn btn-xs btn-default" @click="editing=false">Cancel</button>
	       </div>
	  </div>
	  <div v-text="body" v-show="!editing">
	  </div>
	</div>
	@can('update', $reply)
	<div class="panel-footer level">
	    <button class="btn btn-xs btn-primary mr-1" @click="editing=true">Edit</button>
	    <button class="btn btn-xs btn-danger" @click="destroy">Delete</button>
	    </form>
	</div>
	@endcan
    </div>
</reply>
