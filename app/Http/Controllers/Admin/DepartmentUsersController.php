<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Department;

class DepartmentUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($department_id)
    {
        $department = Department::find($department_id);
        if(!$department) {
            return redirect('admin/departments');
        }

        $title = "{$department->name} Users";

        return view('admin.department.users.index', compact('title', 'department'));
    }
}
