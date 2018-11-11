<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DependencyType extends Model
{
    use SoftDeletes;

    protected $table = 'user_dependency_types';

    protected $fillable = [
        'description'
    ];

    public function dependencies()
    {
        return $this->belongsToMany('App\Models\Dependency');
    }

    public function user_permissions()
    {
        return $this->hasMany('App\Models\UserDependencyPermission', 'user_dependency_type_id', 'id');
    }

}
