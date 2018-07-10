<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Department;
use App\DepartmentUser;
use App\Permission;
use File;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "My Departments";
        $departments = auth()->user()->my_departments();
        return view('department.index', compact('title', 'departments'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $department = Department::find($id);
        if(!$department) {
            return redirect('departments');
        }

        if(auth()->user()->hasPermission('global_administrator') || auth()->user()->hasPermission('department_administrator', $department->id) || auth()->user()->hasPermission('department_user', $department->id)) {
            $title = $department->name;

            return view('department.show', compact('title', 'department'));
        } else {
            return redirect('departments');
        }
    }
}
