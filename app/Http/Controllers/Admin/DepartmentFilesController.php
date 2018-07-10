<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Department;

class DepartmentFilesController extends Controller
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

        $title = "{$department->name} Files";

        return view('admin.department.files.index', compact('title', 'department'));
    }
}
