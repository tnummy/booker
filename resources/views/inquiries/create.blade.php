@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Create New Inquiry</div>

                    <div class="panel-body">

                        <form class="form-horizontal" method="POST" action="{{ route('storeInquiry') }}">

                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('artist') ? ' has-error' : '' }}">
                                <label for="artist" class="col-md-4 control-label">Artist</label>

                                <div class="col-md-6">
                                    <select id="artist" class="form-control" name="artist" value="{{ $inquiryData['artist'] or ""}}" required autofocus>
                                        <option value="" disabled selected>Choose an Artist</option>
                                        @foreach ($artists as $artist)
                                            <option value="{{$artist->id}}">{{$artist->name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('artist'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('artist') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('venue') ? ' has-error' : '' }}">
                                <label for="venue" class="col-md-4 control-label">Venue</label>

                                <div class="col-md-6">
                                    <select id="venue" class="form-control" name="venue" value="{{ $inquiryData['venue'] or ""}}" required autofocus>
                                        <option value="" disabled selected>Choose a Venue</option>
                                        @foreach ($venues as $venue)
                                            <option value="{{$venue->id}}">{{$venue->name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('venue'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('venue') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('date') ? ' has-error' : '' }}">
                                <label for="date" class="col-md-4 control-label">Date</label>

                                <div class="col-md-6">
                                    <input id="date" type="date" class="form-control" name="date" value="{{ $inquiryData['date'] or ''}}" required autofocus>

                                    @if ($errors->has('date'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('date') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
                                <label for="price" class="col-md-4 control-label">Price</label>

                                <div class="col-md-6">
                                    <input id="price" type="number" min="1" step="1" class="form-control" name="price" placeholder="$$$" value="{{ $inquiryData['price'] or ''}}" required autofocus>

                                    @if ($errors->has('price'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('price') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('message') ? ' has-error' : '' }}">
                                <label for="message" class="col-md-4 control-label">Message</label>

                                <div class="col-md-6">
                                    <textarea rows="4" cols="50" id="message" class="form-control" name="message" placeholder="Add a message." required>{{ $inquiryData['message'] or '' }}</textarea>

                                    @if ($errors->has('message'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('message') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                            Submit Inquiry
                                    </button>
                                </div>
                            </div>

                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
