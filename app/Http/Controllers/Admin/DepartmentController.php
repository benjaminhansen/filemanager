<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Department;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::orderBy('name')->get();
        $title = "Manage Departments";
        return view('admin.department.index', compact('title', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Add New Department";
        return view('admin.department.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->name;
        $already_exists = Department::where('name', $name)->first();
        if($already_exists) {
            return redirect()->back()->withMessage('<div class="alert alert-warning">A department with the name ['.$name.'] already exists!</div>')->withInput($request->all());
        }

        $new_department = new Department;
        $new_department->name = $name;
        $new_department->created_by = auth()->user()->id;
        $new_department->enabled = 1;
        $new_department->uri = str_slug($name);
        $new_department->save();

        return redirect('admin/departments/'.$new_department->id);
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
            return redirect('admin/departments');
        }

        $title = $department->name;

        return view('admin.department.show', compact('title', 'department'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $department = Department::find($id);
        if(!$department) {
            return redirect('admin/departments');
        }

        $title = "Edit {$department->name}";

        return view('admin.department.edit', compact('title', 'department'));
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
        $department = Department::find($id);
        if(!$department) {
            return redirect('admin/departments');
        }

        if($department->name != $request->name) {
            $name_already_exists = Department::where('name', $request->name)->first();
            if($name_already_exists) {
                return redirect()->back()->withMessage('<div class="alert alert-warning">Another department with the name ['.$request->name.'] already exists.</div>');
            }
        }

        $department->name = $request->name;
        $department->enabled = $request->status;
        $department->save();

        return redirect('admin/departments/'.$id)->withMessage('<div class="alert alert-success">Department updated.</div>');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $department = Department::find($id);
        if(!$department) {
            return redirect('admin/departments');
        }

        $department->delete();

        return redirect('admin/departments')->withMessage('<div class="alert alert-success">Department deleted.</div>');
    }
}
