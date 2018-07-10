<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Storage;

class DepartmentFile extends Model
{
    protected $appends = ['uploader', 'full_url', 'preview_url'];

    public function getUploaderAttribute()
    {
        return User::find($this->uploaded_by);
    }

    public function getFullUrlAttribute()
    {
        return Storage::url($this->path);
    }

    public function getPreviewUrlAttribute()
    {
        if(strpos($this->mime, 'image') !== false) {
            return Storage::url($this->path);
        } else {
            return url('images/file.png');
        }
    }

    public function department()
    {
        return Department::find($this->department_id);
    }
}
