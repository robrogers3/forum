 @extends('layouts.app')
 @section('content')

    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                Create A Thread
            </div>
            <div class="panel-body">
                <form method="POST" action="/threads">
                  {{csrf_field()}}
                  <div class="form-group">
                  <label for="channel">Channel</label>
                  <select name="channel_id" class="form-control" id="channel">
                  <option disabled {{ old('chonnel_id') ? '' : 'selected'}}>Choose One ..</option>
                    @foreach($channels as $channel)
                        <option {{ old('channel_id') == $channel->id ? 'selected' : ''}} value="{{$channel->id}}">{{$channel->name}}</option>
                    @endforeach
                  </select>
                  </div>

                  <div class="form-group">
                    <label for="title">
                        Title
                    </label>
                    <input id="title" type="text" name="title" class="form-control"
                    placeholder="Just type it out!" value="{{old('title')}}">
                </input>
            </div>
            <div class="form-group">
                <lable for="body">Body</lable>
                <textarea id="body" name="body" class="form-control" placeholder="Your thoughts here!" rows=6>{{old('body')}}
                </textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Post It</button>
            </div>
            @if(count($errors))
            <div class="alert alert-danger">
                <ul class="alert">
                    @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </form>
    </div>
@endsection
