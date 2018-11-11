<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Inquiry extends Model
{
    use SoftDeletes;

    protected $table = 'bookings';
    protected $dates = ['deleted_at'];

    protected $fillable = [
    	'sender_id',
        'receiver_id',
        'current_price',
        'event_date',
        'declined',
        'confirmed',
        'responded'
    ];

    public function negotiations()
    {
        return $this->hasMany('App\Models\Negotiation', 'booking_id');
    }

    public function dependencies()
    {
        return $this->hasMany('App\Models\InquiryDependency', 'booking_id');
    }

    public function initient()
    {
        return $this->hasOne('App\Models\User', 'id', 'sender_id');
    }

    public function recipient()
    {
        return $this->hasOne('App\Models\User', 'id', 'receiver_id');
    }
}