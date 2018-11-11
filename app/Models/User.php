<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'user_type_id',
        'password'
    ];

    protected $hidden = [
        'remember_token'
    ];

    public function dependencies()
    {
        return $this->hasMany('App\Models\Dependency', 'user_type_id', 'id');
    }

    public function allowed_dependency()
    {
        return $this->belongsToMany('App\Models\UserDependencyPermission', 'user_id', 'id');
    }
}