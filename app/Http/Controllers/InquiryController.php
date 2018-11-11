<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dependency;
use App\Models\UserDependencyPermission;
use App\Models\Inquiry;
use App\Models\InquiryDependency;
use App\Models\Negotiation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InquiryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendNegotiation(Request $request, $id)
    {   
        $userId  = Auth::id();
        $inquiry = Inquiry::where('id', $id)->firstorfail();
        $price   = $request->input('price');
        $message = $request->input('message');

        $receiverId = ($userId == $inquiry->receiver_id) ? $inquiry->sender_id : $inquiry->receiver_id;

        $data = [
            'booking_id'  => $id,
            'sender_id'   => $userId,
            'receiver_id' => $receiverId,
            'offer_price' => $price,
            'message'     => $message,
            ];

        Negotiation::create($data);

        $inquiry->current_price = $price;
        $inquiry->responded = true;
        $inquiry->save();

        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $userId  = Auth::id();
        $userAllowed = UserDependencyPermission::select('user_dependency_type_id')->where('user_id', $userId)->get();
        
        if ($userAllowed->contains('user_dependency_type_id', 1)) {
            $artists = Dependency::where('user_dependency_type_id', 1)->where('user_id', $userId)->get();
        } else {
            $artists = Dependency::where('user_dependency_type_id', 1)->get();
        }
        if ($userAllowed->contains('user_dependency_type_id', 2)) {
            $venues = Dependency::where('user_dependency_type_id', 2)->where('user_id', $userId)->get();
        } else {
            $venues = Dependency::where('user_dependency_type_id', 2)->get();
        }

        return view('inquiries/create')->with(['artists' => $artists, 'venues'=> $venues]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userId  = Auth::id();
        $message = $request->input('message');
        $price   = $request->input('price');
        $date    = $request->input('date');
        $artist  = $request->input('artist');
        $venue   = $request->input('venue');

        $artistParent = Dependency::where('id', $artist)->firstorfail();
        $venueParent  = Dependency::where('id', $venue)->firstorfail();

        $recipientId = ($userId == $artistParent->user_id) ? $venueParent->user_id : $artistParent->user_id;

        $recipient  = User::where('id', $recipientId)->firstorfail();

        $data = [
            'sender_id'     => $userId,
            'receiver_id'   => $recipientId,
            'current_price' => $price,
            'event_date'    => $date,
            ];

        $bookingId = Inquiry::create($data)->id;

        $data = [
            'booking_id'  => $bookingId,
            'sender_id'   => $userId,
            'receiver_id' => $recipientId,
            'offer_price' => $price,
            'message'     => $message,
            ];

        Negotiation::create($data);

        $data = [
            ['user_dependency_id'=>$artist, 'booking_id'=>$bookingId],
            ['user_dependency_id'=>$venue,  'booking_id'=>$bookingId],
        ];

        InquiryDependency::insert($data);

        $message = sprintf('Your inquiry was sent to %s %s!', $recipient->first_name, $recipient->last_name);

        $request->session()->flash('status', $message);

        return redirect('/home');
    }
}
