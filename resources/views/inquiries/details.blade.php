@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">

                    <div class="panel-heading">
                        Inquiry Details            
                        @if ($inquiry->confirmed)
                            (<strong class="text-success">confirmed</strong> on {{$inquiry->updated_at}}) 
                        @elseif ($inquiry->declined)
                            (<strong class="text-error">declined</strong> on {{$inquiry->updated_at}})
                        @elseif ($inquiry->event_date < date('Y-m-d'))
                            (expired)
                        @else
                            (pending)
                        @endif
                    </button></div>
                    <div class="panel-body">
                        <ul class="list-unstyled list-inline">
                            <li><strong>Current Offer:</strong> ${{$inquiry->current_price}}</li>
                            <li><strong>Event Date:</strong> {{$inquiry->event_date}}</li>
                        </ul>
                        <ul class="list-unstyled list-inline">
                            @foreach ($inquiry->dependencies as $dependency)
                                <li><strong>{{$dependency->dependency->type_description->description}}:</strong> {{$dependency->dependency->name}}</li>
                            @endforeach
                        </ul>
                        @if (!$inquiry->confirmed && !$inquiry->declined && !$inquiry->expired)
                            <ul class="list-unstyled list-inline">
                                <li><button><a href="{{route('confirmInquiry', $inquiry->id)}}">confirm</a></button></li>
                                <li><button><a href="{{route('declineInquiry', $inquiry->id)}}">decline</a></button></li>
                            </ul>
                        @endif
                    </div>
                
                </div>

                <div class="panel panel-default">

                    <div class="panel-heading">Send New Offer</button></div>

                    <div class="panel-body">

                        <form class="form-horizontal" method="POST" action="{{ route('sendNegotiation', $inquiry->id) }}">

                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}" class="invisible">
                                <label for="price" class="col-md-4 control-label">Price</label>

                                <div class="col-md-6">
                                    <input id="price" type="number" min="1" step="1" class="form-control" name="price" placeholder="$$$" value="{{ $inquiry->current_price or ''}}" required autofocus @if ($inquiry->confirmed || $inquiry->declined || $inquiry->expired) readonly @endif>

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
                                    <textarea rows="4" cols="50" id="message" class="form-control" name="message" placeholder="Send new message." required></textarea>

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
                                            Negotiate
                                    </button>
                                </div>
                            </div>

                            </form>
                    </div>
                </div>

            <div class="panel panel-default">
                @if($inquiry->recipient->id != Auth::user()->id)
                    <div class="panel-heading">Negoitiation History with {{$inquiry->recipient->first_name}} {{$inquiry->recipient->last_name}}</div>
                @else
                    <div class="panel-heading">Negoitiation History with {{$inquiry->initient->first_name}} {{$inquiry->initient->last_name}}</div>
                @endif
                <div class="panel-body">
                    @foreach ($inquiry->negotiations as $negotiation)
                        <h4> {{$negotiation->initient->first_name}} </h4>
                        <strong>Offer: ${{$negotiation->offer_price}}</strong> {{$negotiation->message}}
                        <h5><small>{{$negotiation->created_at}}</small></h5>
                        <hr>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
@endsection
