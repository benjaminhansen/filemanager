<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Permission;

class PermissionsController extends Controller
{
    public function permissions()
    {
        $exclude = [1];
        return Permission::whereNotIn('id', $exclude)->orderBy('name')->get();
    }
}
