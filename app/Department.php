<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $appends = ['users', 'files', 'creator', 'dir_path'];

    public function getUsersAttribute()
    {
        return DepartmentUser::where('department_id', $this->id)->get();
    }

    public function getFilesAttribute()
    {
        return DepartmentFile::where('department_id', $this->id)->get();
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
