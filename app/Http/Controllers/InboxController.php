<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Inquiry;
use App\Models\InquiryDependency;
use App\Models\Negotiation;
use Carbon\Carbon;

class InboxController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function inquiryDetails(Request $request, $id)
    {
        $now = Carbon::now();
        $userId  = Auth::id();

        $negotiations = Negotiation::where('booking_id', $id)
                ->where('receiver_id', $userId)
                ->where('dismissed', 0)
                ->get();
        foreach ($negotiations as $negotiation) {
            $negotiation->dismissed = true;
            $negotiation->save();
        }

        $inquiry = Inquiry::with('negotiations.recipient')
            ->with('recipient')
            ->with('dependencies.dependency.type_description')
            ->with(['negotiations' => function ($q) {
                $q->orderBy('created_at', 'desc');
            }])
            ->find($id);

        $inquiry->expired = $inquiry->event_date < $now;

        return view('inquiries/details')->with(['inquiry' => $inquiry]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function confirmInquiry(Request $request, $id)
    {
        $inquiry = Inquiry::find($id);
        $inquiry->confirmed = true;
        $inquiry->responded = true;
        $inquiry->save();

        $userId  = Auth::id();
        $inquiry = Inquiry::where('id', $id)->firstorfail();
        $price   = $inquiry->current_price;
        $message = 'Confimed';

        $receiverId = ($userId == $inquiry->receiver_id) ? $inquiry->sender_id : $inquiry->receiver_id;

        $data = [
            'booking_id'  => $id,
            'sender_id'   => $userId,
            'receiver_id' => $receiverId,
            'offer_price' => $price,
            'message'     => $message,
            ];

        Negotiation::create($data);

        $negotiations = Negotiation::where('booking_id', $id)
                ->where('receiver_id', $userId)
                ->where('dismissed', 0)
                ->get();
        foreach ($negotiations as $negotiation) {
            $negotiation->dismissed = true;
            $negotiation->save();
        }

        $message = sprintf('Confirmed!');
        $request->session()->flash('status', $message);

        return redirect()->back();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function declineInquiry(Request $request, $id)
    {
        $userId  = Auth::id();

        $inquiry = Inquiry::find($id);
        $inquiry->declined = true;
        $inquiry->responded = true;
        $inquiry->save();

        $price   = $inquiry->current_price;
        $message = 'Declined';

        $receiverId = ($userId == $inquiry->receiver_id) ? $inquiry->sender_id : $inquiry->receiver_id;

        $data = [
            'booking_id'  => $id,
            'sender_id'   => $userId,
            'receiver_id' => $receiverId,
            'offer_price' => $price,
            'message'     => $message,
            ];

        Negotiation::create($data);

        $negotiations = Negotiation::where('booking_id', $id)
                ->where('receiver_id', $userId)
                ->where('dismissed', 0)
                ->get();
        foreach ($negotiations as $negotiation) {
            $negotiation->dismissed = true;
            $negotiation->save();
        }

        $message = sprintf('Declined!');
        $request->session()->flash('status', $message);

        return redirect()->back();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dismissNotification(Request $request, $id)
    {
        $userId  = Auth::id();

        $negotiation = Negotiation::find($id);
        
        $negotiations = Negotiation::where('booking_id', $negotiation->booking_id)
                ->where('receiver_id', $userId)
                ->where('dismissed', 0)
                ->get();
        foreach ($negotiations as $negotiation) {
            $negotiation->dismissed = true;
            $negotiation->save();
        }


        $message = sprintf('Notification was dismissed!');
        $request->session()->flash('status', $message);

        return redirect('/home');
    }
}
