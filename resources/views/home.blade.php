@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if (isset($negotiations) && count($negotiations) > 0)
                <div class="panel panel-primary">
                    <div class="panel-heading">New Negotiations Waiting a Response ({{count($negotiations)}})</div>

                    <div class="panel-body">
                        <ul>
                            @foreach ($negotiations as $negotiation)
                                <li><strong>{{$negotiation->initient->first_name}} {{$negotiation->initient->last_name}}</strong> "{{$negotiation->message}}"</li>
                                <ul>
                                    <li>Sent: {{$negotiation->created_at}}</li>
                                    <li>Price: ${{$negotiation->inquiry->current_price}}</li>
                                    <li>Event Date: {{$negotiation->inquiry->event_date}}</li>
                                    @foreach ($negotiation->inquiry->dependencies as $dep)
                                        <li>{{$dep->dependency->type_description->description}}: {{$dep->dependency->name}}</li>
                                    @endforeach
                                    <li>
                                        <a href="{{route('inquiryDetails', $negotiation->inquiry->id)}}">respond</a>
                                        @if (!$negotiation->inquiry->confirmed && !$negotiation->inquiry->declined && !$negotiation->inquiry->expired)
                                            ,  
                                            <a href="{{route('confirmInquiry', $negotiation->inquiry->id)}}">confirm</a>, 
                                            <a href="{{route('declineInquiry', $negotiation->inquiry->id)}}">decline</a>, 
                                        @endif
                                        or 
                                        <a href="{{ route('dismissNotification', $negotiation->id) }}">dismiss</a></li>
                                </ul>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif           

            @if (isset($requests) && count($requests) > 0)
                <div class="panel panel-primary">
                    <div class="panel-heading">New Incoming Requests ({{count($requests)}})</div>
                    <div class="panel-body">
                        <ul>
                            @foreach ($requests as $request)
                                <li>Request for ${{$request->inquiry->current_price}} on {{$request->inquiry->event_date}}</li>
                                <ul>
                                    @foreach ($request->inquiry->dependencies as $dep)
                                        <li><strong>{{$dep->dependency->type_description->description}}:</strong> {{$dep->dependency->name}}</li>
                                    @endforeach
                                </ul>
                                <li><a href="{{route('inquiryDetails', $request->inquiry->id)}}">respond</a> or <a href="{{ route('dismissNotification', $request->id) }}">dismiss</a></li>
                            @endforeach
                        </ul>

                    </div>
                </div>
            @endif

            <div class="panel panel-default">

                <div class="panel-heading">
                    @if(!Request::is('home'))
                        <a href="{{ url('/home') }}">Home</a> -> 
                    @endif
                    @if(Request::is('home'))
                        Booking Dashboard 
                    @elseif(Request::is('dependency/*'))
                        Dependencies Dashboard
                    @elseif(Request::is('inbox/*'))
                        Inquiries Dashboard
                    @elseif(Request::is('events'))
                        Events Dashboard
                    @endif
                    @if(!Request::is('dependency/*'))
                    <a class="right" href="{{ route('createInquiry') }}">+ Create New Inquiry</a></div>
                    @else
                            <a class="right" href="{{ route('createDependency') }}">+ Add New Dependency</a></div>
                    @endif
                <div class="panel-body">
                    <ul class="list-unstyled list-inline">
                        <li>
                            <button><a href="{{ route('allNegotiations') }}" @if(Request::is('inbox/negotiations/all')) class="text-warning" @endif>View All Negotiations ({{$newNegotiationsCount}})</a></button>
                        </li>
                        <li>
                            <button><a href="{{ route('allRequests') }}" @if(Request::is('inbox/requests/all')) class="text-warning" @endif>View All Requests ({{$newRequestsCount}})</a></button>
                        </li>
                        <li>
                            <button><a href="{{ route('allDependencies') }}" @if(Request::is('dependency/all')) class="text-warning" @endif>Manage Dependencies</a></button>
                        </li>
                    </ul>
                    <hr>
                    @if(Request::is('inbox/*'))
                        @include('components.inbox')
                    @elseif(Request::is('dependency/*'))
                        @include('components.dependencies')
                    @else
                        @if(Request::is('home'))
                            <h4>Upcoming (confirmed) events: <small><a class="right" href="{{ route('events') }}">See all</a></small></h4>
                        @elseif(Request::is('events'))
                            <h4>All events: </h4>
                        @endif
                        @if(!count($inquiries) > 0)
                            No upcoming events!
                        @endif
                        @include('components.events')
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
