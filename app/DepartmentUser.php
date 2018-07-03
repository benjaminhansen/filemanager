<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DepartmentUser extends Model
{
    protected $appends = ['user', 'permission'];

    public function getUserAttribute()
    {
        return User::find($this->user_id);
    }

    public function getPermissionAttribute()
    {
        return Permission::find($this->permission_id);
    }
}
