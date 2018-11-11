<ul>
    @foreach ($inboxItems as $item)
    <div>
        <li><strong>Event on {{$item->inquiry->event_date}} for ${{$item->inquiry->current_price}}</strong> 
            @if ($item->inquiry->confirmed)
                (confirmed)
            @elseif ($item->inquiry->declined)
                (declined)
            @elseif ($item->expired)
                (expired)
            @else
                (pending)
            @endif
            @if(!$item->dismissed && Auth::user()->id != $item->sender_id)
                <button type="button" class="btn btn-info"><a href="{{route('inquiryDetails', $item->inquiry->id)}}">New!</a></button>
            @endif
        </li>
        <ul>
            @foreach ($item->inquiry->dependencies as $item_dependency)
                <li><strong>{{$item_dependency->dependency->type_description->description}}:</strong> {{$item_dependency->dependency->name}}</li>
            @endforeach
            @if ($item->expired || $item->inquiry->confirmed || $item->inquiry->declined)
                <li><a href="{{route('inquiryDetails', $item->inquiry->id)}}">view details</a></li>
            @else
                <li><a href="{{route('inquiryDetails', $item->inquiry->id)}}">view details and respond</a>, <a href="{{route('confirmInquiry', $item->inquiry->id)}}">confirm</a>, or <a href="{{route('declineInquiry', $item->inquiry->id)}}">decline</a></li>
            @endif
        </ul>
    </div>
    @endforeach
</ul>