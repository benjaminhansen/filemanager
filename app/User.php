<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fname', 'lname', 'email',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function hasPermission($permission_slug, $department_id = null)
    {
        $permission = Permission::where('slug', $permission_slug)->first();
        if($permission) {
            if(is_null($department_id)) {
                $has_permission = DepartmentUser::where('user_id', $this->id)->where('permission_id', $permission->id)->first();
                if($has_permission) {
                    return true;
                } else {
                    return false;
                }
            } else {
                $has_permission = DepartmentUser::where('department_id', $department_id)->where('user_id', $this->id)->where('permission_id', $permission->id)->first();
                if($has_permission) {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    public function canAccessDepartment($department_id)
    {
        if($this->hasPermission('global_administrator') || $this->hasPermission('department_administrator', $department_id) || $this->hasPermission('department_user', $department_id)) {
            return true;
        } else {
            return false;
        }
    }

    public function my_departments()
    {
        if($this->hasPermission('global_administrator')) {
            return Department::orderBy('name')->get();
        } else {
            $my_departments = DepartmentUser::where('user_id', $this->id)->get();
            $ids = [];
            foreach($my_departments as $dept) {
                $ids[] = $dept->department_id;
            }
            return Department::whereIn('id', $ids)->where('enabled', 1)->orderBy('name')->get();
        }
    }

    public function my_recent_uploads()
    {
        $five_days_ago = date("Y-m-d G:i:s", strtotime("-5 days"));
        $files = DepartmentFile::where('uploaded_by', $this->id)->where('created_at', '>', $five_days_ago)->orderBy('created_at', 'desc')->get();
        return $files;
    }

    public function my_departments_recent_uploads()
    {
        $my_departments = $this->my_departments();
        $return_files = [];
        $five_days_ago = date("Y-m-d G:i:s", strtotime("-5 days"));
        foreach($my_departments as $dept) {
            $files = DepartmentFile::where('uploaded_by', '<>', $this->id)->where('department_id', $dept->id)->where('created_at', '>', $five_days_ago)->orderBy('created_at', 'desc')->get();
            foreach($files as $file) {
                $return_files[] = $file;
            }
        }
        return $return_files;
    }
}
