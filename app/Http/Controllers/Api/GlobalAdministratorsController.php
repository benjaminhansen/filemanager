<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DepartmentUser;
use App\User;

class GlobalAdministratorsController extends Controller
{
    public function get_global_administrators()
    {
        if(auth()->user()->hasPermission('global_administrator')) {
            $global_admins = DepartmentUser::where('permission_id', 1)->get();
            return $global_admins;
        }
    }

    public function add_global_administrator(Request $request)
    {
        if(auth()->user()->hasPermission('global_administrator')) {
            $user_exists = User::where('username', $request->username)->first();
            if($user_exists) {
                $user_is_global_administrator = DepartmentUser::where('user_id', $user_exists->id)->where('permission_id', 1)->first();
                if($user_is_global_administrator) {
                    return json_encode([
                        'success' => false,
                        'message' => 'This user is already a Global Administrator.'
                    ]);
                } else {
                    DepartmentUser::where('user_id', $user_exists->id)->delete();

                    $permission = new DepartmentUser;
                    $permission->user_id = $user_exists->id;
                    $permission->permission_id = 1;
                    $permission->save();

                    return json_encode([
                        'success' => true,
                        'message' => 'New permission applied successfully.'
                    ]);
                }
            } else {
                return json_encode([
                    'success' => false,
                    'message' => 'No user could be found with the username ['.$request->username.']. Make sure to have the user authenticate first so their internal user ID can be generated.'
                ]);
            }
        }
    }

    public function delete_global_administrator(Request $request)
    {
        if(auth()->user()->hasPermission('global_administrator')) {
            $exists = DepartmentUser::where('user_id', $request->userid)->where('permission_id', 1)->first();
            if($exists) {
                $exists->delete();
            }
        }
    }
}
