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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->hasPermission('global_administrator')) {
            return redirect('dashboard');
        }

        $title = "Create A New Department";
        return view('department.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!auth()->user()->hasPermission('global_administrator')) {
            return redirect('dashboard');
        }

        $new_department = new Department;
        $new_department->name = $request->name;
        $new_department->created_by = auth()->user()->id;
        $new_department->enabled = 1;
        $new_department->uri = str_slug($request->name);
        $new_department->save();

        $dir_path = $new_department->dir_path;
        File::makeDirectory($dir_path, 0777, true);

        return redirect('department/'.$new_department->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
