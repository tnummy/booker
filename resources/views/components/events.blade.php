
<ul>
    @foreach ($inquiries as $inquiry)
        <li><strong>Event on {{$inquiry->event_date}} for ${{$inquiry->current_price}}</strong> 
            @if ($inquiry->confirmed)
                (confirmed)
            @elseif ($inquiry->event_date < date('Y-m-d'))
                (expired)
            @else
                (pending)
            @endif
        </li>
        <ul>
            @if (Auth::user()->id == $inquiry->recipient->id)
                <li><strong>Contact:</strong> {{$inquiry->initient->first_name}} {{$inquiry->initient->last_name}}; {{$inquiry->initient->email}}</li>
            @else
                <li><strong>Contact:</strong> {{$inquiry->recipient->first_name}} {{$inquiry->recipient->last_name}}; {{$inquiry->recipient->email}}</li>
            @endif 
            @foreach ($inquiry->dependencies as $dep)
                <li><strong>{{$dep->dependency->type_description->description}}:</strong> {{$dep->dependency->name}}</li>
            @endforeach
            <li><a href="{{route('inquiryDetails', $inquiry->id)}}">more details</a></li>
        </ul>
    @endforeach
</ul>