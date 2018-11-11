<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dependency extends Model
{
    use SoftDeletes;

    protected $table = 'user_dependencies';

    protected $fillable = [
        'name',
        'user_id',
        'user_dependency_type_id'
    ];
    
    public function type_description()
    {
        return $this->hasOne('App\Models\DependencyType', 'id', 'user_dependency_type_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function bookings()
    {
        return $this->belongsToMany('App\Models\Inquiry', 'user_dependencies', 'id', 'user_dependency_id');
    }
}
