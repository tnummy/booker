<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDependencyPermission extends Model
{
    use SoftDeletes;

	public $timestamps = false;

    protected $table = 'user_dependency_permissions';
    protected $fillable = [
        'user_dependency_type_id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function dependency_type()
    {
        return $this->belongsTo('App\Models\DependencyType', 'user_dependency_type_id', 'id');
    }
}
