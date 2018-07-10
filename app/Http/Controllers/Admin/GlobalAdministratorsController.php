<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GlobalAdministratorsController extends Controller
{
    public function index()
    {
        $title = "Manage Global Administrators";
        return view('admin.globaladministrators.index', compact('title', 'global_admins'));
    }
}
