            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="level">
                        <h4 class="flex">
                            <a href="{{$activity->subject->path()}}">
                                {{$activity->subject->title}}
                            </a>
                        </h4>
                        <strong>
                            with {{$activity->subject->replies_count}} {{str_plural('reply', $activity->subject->replies_count)}}
                        </strong>
                    </div>
                    <div class="level">
                        <h4 class="flex">Published in channel
                            <a href="{$activity->subject->channel->name}"> {$activity->subject->channel->name} </a>
                        </h4>
                        <h5>{{$activity->subject->created_at->diffForHumans()}}</h5>
                    </div>
                </div>
                <div class="panel-body">
                    <article>
                        <div class="body">{{$activity->subject->body}}</div>
                    </article>
                </div>

            </div>