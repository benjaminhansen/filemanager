<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Department;
use App\Permission;
use App\DepartmentPermissionGroup;

class DepartmentGroupsController extends Controller
{
    public function index($department_id)
    {
        $department = Department::find($department_id);
        if(!$department) {
            return redirect('admin/departments')->withMessage(['typeid' => 'danger', 'message' => 'Department not found!', 'timeout' => 5]);
        }
        $permissions = Permission::where('slug', '<>', 'global_administrator')->get();
        $title = "LDAP Group Management";
        return view('admin.groups.index', compact('title', 'department', 'permissions'));
    }

    public function save(Request $request)
    {
        foreach($request->except('_token') as $key => $value) {
            $key_parts = explode("_", $key);
            $department_id = $key_parts[0];
            $permission_id = $key_parts[1];

            $permission_group = DepartmentPermissionGroup::where('department_id', $department_id)->where('permission_id', $permission_id)->first();
            if($permission_group) {
                $permission_group->ldap_group_dn = $value;
                $permission_group->save();
            } else {
                $new_permission_group = new DepartmentPermissionGroup;
                $new_permission_group->department_id = $department_id;
                $new_permission_group->permission_id = $permission_id;
                $new_permission_group->ldap_group_dn = $value;
                $new_permission_group->save();
            }
        }

        return redirect('admin/departments/'.$department_id)->withMessage(['typeid' => 'success', 'message' => 'Department groups saved!', 'timeout' => 5]);
    }
}
