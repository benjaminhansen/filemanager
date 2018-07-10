<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;

    protected $appends = ['users', 'files', 'creator', 'dir_path'];
    protected $dates = ['deleted_at'];

    public function getUsersAttribute()
    {
        return DepartmentUser::where('department_id', $this->id)->get();
    }

    public function getFilesAttribute()
    {
        return DepartmentFile::where('department_id', $this->id)->orderBy('created_at', 'desc')->get();
    }

    public function getCreatorAttribute()
    {
        return User::find($this->created_by);
    }

    public function getDirPathAttribute()
    {
        return env('STORAGE_ROOT') . "/" . $this->uri;
    }
}
