<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Inquiry;
use App\Models\InquiryDependency;
use App\Models\Negotiation;
use App\Models\Dependency;
use Carbon\Carbon;

class HomeController extends Controller
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

    public function newNegotiationsCount($userId)
    {
        $now = Carbon::now();
        return Negotiation::with('inquiry.dependencies')
            // ->where(function ($query) use ($userId) {
            //     $query->where('sender_id', $userId)
            //           ->orWhere('receiver_id', $userId);
            // })
            ->whereHas('inquiry', function($q) use ($now, $userId){
                $q->where('responded', 1);
            })
            ->where('receiver_id', $userId)
            ->where('dismissed', 0)
            ->orderBy('created_at', 'desc')
            ->groupBy('booking_id')
            ->get()
            ->count();
    }

    public function newRequestsCount()
    {
        $userId = Auth::id();
        $now = Carbon::now();
        return Negotiation::where('dismissed', 0)
            ->whereHas('inquiry', function($q) use ($now, $userId){
                $q->where('responded', 0);
            })
            ->where('receiver_id', $userId)
            ->orderBy('created_at', 'desc')
            ->groupBy('booking_id')
            ->get()
            ->count();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function homePageEvents()
    {
        $userId = Auth::id();

        $now = Carbon::now();

        $inquiries = Inquiry::with('dependencies.dependency.type_description')
            ->with('initient')
            ->with('recipient')
            ->where(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->orWhere('receiver_id', $userId);
            })
            ->where('event_date', '>=', $now)
            ->where('confirmed', 1)
            ->orderBy('event_date', 'desc')
            ->take(10)
            ->get();

        $negotiations = Negotiation::with('inquiry.dependencies')
            // ->where(function ($query) use ($userId) {
            //     $query->where('sender_id', $userId)
            //           ->orWhere('receiver_id', $userId);
            // })
            ->whereHas('inquiry', function($q) use ($now, $userId){
                $q->where('responded', 1);
            })
            ->where('receiver_id', $userId)
            ->where('dismissed', 0)
            ->orderBy('created_at', 'desc')
            ->groupBy('booking_id')
            ->take(3)
            ->get();


        $requests = Negotiation::with('inquiry.dependencies')
            ->whereHas('inquiry', function($q) use ($now, $userId){
               $q->where('event_date', '>=', $now)
                ->where('confirmed', 0)
                ->where('declined',  0)
                ->where('responded', 0);
            })
            ->where('dismissed', 0)
            ->where('receiver_id', $userId)
            ->groupBy('booking_id')
            ->orderBy('created_at', 'desc')
            ->get()
            ->take(3);

        $newNegotiationsCount = $this->newNegotiationsCount($userId);
        $newRequestsCount = $this->newRequestsCount();

        return view('home')->with(['inquiries' => $inquiries, 'negotiations' => $negotiations, 'requests' => $requests, 'newNegotiationsCount' => $newNegotiationsCount, 'newRequestsCount' => $newRequestsCount]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function allEvents()
    {
        $userId = Auth::id();

        $now = Carbon::now();

        $inquiries = Inquiry::with('dependencies.dependency.type_description')
            ->with('initient')
            ->with('recipient')
            ->where(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->orWhere('receiver_id', $userId);
            })
            ->orderBy('event_date', 'desc')
            ->get();

        $newNegotiationsCount = $this->newNegotiationsCount($userId);
        $newRequestsCount = $this->newRequestsCount();

        return view('home')->with(['inquiries' => $inquiries, 'newNegotiationsCount' => $newNegotiationsCount, 'newRequestsCount' => $newRequestsCount]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function allRequests()
    {
        $userId = Auth::id();

        $now = Carbon::now();

        $inboxItems = Negotiation::with('inquiry.dependencies.dependency.type_description')
            ->whereHas('inquiry', function($q) use ($now, $userId){
                $q->where('responded', 0);
            })
            ->with('initient')
            ->with('recipient')
            ->where('receiver_id', $userId)
            ->orderBy('created_at', 'desc')
            ->groupBy('booking_id')
            ->get();
        foreach ($inboxItems as $item) {
            $item->expired = $item->inquiry->event_date < $now;
        }
        $newNegotiationsCount = $this->newNegotiationsCount($userId);
        $newRequestsCount = $this->newRequestsCount();

        return view('home')->with(['inboxItems' => $inboxItems, 'newNegotiationsCount' => $newNegotiationsCount, 'newRequestsCount' => $newRequestsCount]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function allNegotiations()
    {
        $userId = Auth::id();

        $now = Carbon::now();

        $inboxItems = Negotiation::with('inquiry.dependencies.dependency')
            ->where(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->orWhere('receiver_id', $userId);
            })
            ->whereHas('inquiry', function($q) use ($now, $userId){
                $q->where('responded', 1);
            })
            ->orderBy('created_at', 'desc')
            ->groupBy('booking_id')
            ->get();

        $newNegotiationsCount = $this->newNegotiationsCount($userId);
        $newRequestsCount = $this->newRequestsCount();

        return view('home')->with(['inboxItems' => $inboxItems, 'newNegotiationsCount' => $newNegotiationsCount, 'newRequestsCount' => $newRequestsCount]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function allDependencies()
    {
        $userId = Auth::id();

        $now = Carbon::now();

        $dependencies = Dependency::with('type_description')
            ->withTrashed()
            ->where('user_id', $userId)
            ->orderBy('name')
            ->get();

        $newNegotiationsCount = $this->newNegotiationsCount($userId);
        $newRequestsCount = $this->newRequestsCount();

        return view('home')->with(['dependencies' => $dependencies, 'newNegotiationsCount' => $newNegotiationsCount, 'newRequestsCount' => $newRequestsCount]);
    }
}
