<div class="panel panel-default">
    <div class="panel-heading">
        <div class="level">
        <h5 class="flex">{{$reply->owner->name}} said on {{$reply->created_at->diffForHumans()}}</h5>
        <form action="/reply/{{$reply->id}}/favorites" method="POST">
            {{csrf_field()}}
            <button type="submit" class="btn btn-default btn-sm" {{ $reply->isFavorited() ? 'disabled' : ''}}>
            {{$reply->favorites()->count()}} {{str_plural('Favorite', $reply->favorites()->count()) }}
            </button>
        </form>
        </div>
    </div>
    <div class="panel-body">
        <div class="body">{{$reply->body}}</div>
    </div>
</div>