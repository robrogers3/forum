<div class="panel panel-default">
    <div class="panel-heading">
        <div class="level">
        <h5 class="flex"><a href="/profiles/{{$reply->owner->name}}">{{$reply->owner->name}}</a>  said  {{$reply->created_at->diffForHumans()}}</h5>
        <form action="/reply/{{$reply->id}}/favorites" method="POST">
            {{csrf_field()}}
            <button type="submit" class="btn btn-default btn-sm" {{ $reply->isFavorited() ? 'disabled' : ''}}>
            {{$reply->favorites_count}} {{str_plural('Favorite', $reply->favorites_count) }}
            </button>
        </form>
        </div>
    </div>
    <div class="panel-body">
        <div class="body">{{$reply->body}}</div>
    </div>
</div>