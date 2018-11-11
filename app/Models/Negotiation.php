<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Negotiation extends Model
{
    use SoftDeletes;

    protected $table = 'negotiation_history';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'booking_id',
    	'sender_id',
        'receiver_id',
        'offer_price',
        'message',
        'dismissed',
    ];

    public function inquiry()
    {
        return $this->belongsTo('App\Models\Inquiry', 'booking_id');
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