 @extends('layouts.app')
 @section('content')
 <div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-info">
                <div class="panel-heading">{{$thread->creator->name}} posted {{$thread->title}} on {{$thread->created_at->diffForHumans()}}</div>
                <div class="panel-body">
                    <div class="body">{{$thread->body}}</div>
                </div>
            </div>
            @foreach($replies as $reply)
            @include('threads.reply')
            @endforeach
            {{$replies->links()}}
            @if(auth()->check())
            <form action="{{$thread->path()}}/replies" method="POST">
                {{csrf_field()}}
                <div class="form-group">
                    <textarea class="form-control" name="body" placeholder="What have you got to say?">
                    </textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            @else
            <p class="text-center">Please login</p>
            @endif
        </div>
        <div class="col-md-4">
            <div class="panel panel-info">
                <div class="panel-body">
                    <div class="body">
                        This thread was published  {{$thread->created_at->diffForHumans()}}
                        by <a href="#">{{$thread->creator->name}}</a>
                        and currently has {{$thread->replies_count}} {{str_plural('comment', $thread->replies_count)}}.
                    </div>
                </div>
            </div>"
        </div>
    </div>
    @endsection