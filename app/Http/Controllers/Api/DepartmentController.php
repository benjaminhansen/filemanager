<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Department;

class DepartmentController extends Controller
{
    public function department($id = null)
    {
        if(is_null($id)) {
            if(auth()->user()->hasPermission('global_administrator')) {
                $departments = Department::all();
                return json_encode([
                    'success' => true,
                    'message' => 'All departments have been returned.',
                    'data' => $departments
                ]);
            } else {
                return json_encode([
                    'success' => false,
                    'message' => 'You do not have permission to access this area.'
                ]);
            }
        } else {
            $department = Department::find($id);
            if($department) {
                if(auth()->user()->hasPermission('global_administrator') || auth()->user()->hasPermission('department_administrator', $department->id)) {
                    return json_encode([
                        'success' => true,
                        'message' => 'Department has been returned.',
                        'data' => $department
                    ]);
                } else {
                    return json_encode([
                        'success' => false,
                        'message' => 'You do not have permission to access this area.'
                    ]);
                }
            } else {
                return json_encode([
                    'success' => false,
                    'message' => 'Department not found!'
                ]);
            }
        }
    }
}
