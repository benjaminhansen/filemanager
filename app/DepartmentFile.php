<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DepartmentFile extends Model
{
    protected $appends = ['uploader'];

    public function getUploaderAttribute()
    {
        return User::find($this->uploaded_by);
    }
}
