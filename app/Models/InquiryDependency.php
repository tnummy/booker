<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class InquiryDependency extends Model
{
    use SoftDeletes;

    protected $table = 'booking_dependencies';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'booking_id',
        'user_dependency_id'
    ];

    public function bookings()
    {
        return $this->belongsToMany('App\Models\Inquiry');
    }

    public function dependency()
    {
        return $this->belongsTo('App\Models\Dependency', 'user_dependency_id');
    }
}