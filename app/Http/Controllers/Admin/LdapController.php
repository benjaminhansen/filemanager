<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LdapAttribute;
use App\DepartmentPermissionGroup;

class LdapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "LDAP Management";
        $attributes = LdapAttribute::all();
        $admin_group = DepartmentPermissionGroup::where('department_id', '-1')->first();
        return view('admin.ldap.index', compact('title', 'attributes', 'admin_group'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->ldap_update == "attributes") {
            $attributes = LdapAttribute::all();
            foreach($attributes as $attribute) {
                $request_attribute = $request->{$attribute->local_attribute};
                $update_attribute = LdapAttribute::where('local_attribute', $attribute->local_attribute)->first();
                $update_attribute->ldap_attribute = $request_attribute;
                $update_attribute->save();
            }
            return redirect('/')->withMessage(['typeid' => 'success', 'message' => 'LDAP Attributes updated successfully.', 'timeout' => 5]);
        }

        if($request->ldap_update == "admins") {
            $group = DepartmentPermissionGroup::where('department_id', '-1')->first();
            $group->ldap_group_dn = $request->group_dn;
            $group->save();
            
            return redirect('/')->withMessage(['typeid' => 'success', 'message' => 'Admin LDAP group updated successfully.', 'timeout' => 5]);
        }
    }
}
