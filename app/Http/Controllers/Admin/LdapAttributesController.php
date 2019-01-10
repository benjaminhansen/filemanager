<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LdapAttribute;

class LdapAttributesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "LDAP Attribute Management";
        $attributes = LdapAttribute::all();
        return view('admin.ldapattrs.index', compact('title', 'attributes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = LdapAttribute::all();
        foreach($attributes as $attribute) {
            $request_attribute = $request->{$attribute->local_attribute};
            $update_attribute = LdapAttribute::where('local_attribute', $attribute->local_attribute)->first();
            $update_attribute->ldap_attribute = $request_attribute;
            $update_attribute->save();
        }
        return redirect('/')->withMessage(['typeid' => 'success', 'message' => 'LDAP Attributes updated successfully.', 'timeout' => 5]);
    }
}
