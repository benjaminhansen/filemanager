<?php

namespace App\Support;

use App\User;
use App\DepartmentPermissionGroup;
use App\DepartmentUser;
use App\Permission;

class Helpers
{
    public static function cidr_match($ip, $range)
    {
        list ($subnet, $bits) = explode('/', $range);
        $ip = ip2long($ip);
        $subnet = ip2long($subnet);
        $mask = -1 << (32 - $bits);
        $subnet &= $mask; # nb: in case the supplied subnet wasn't correctly aligned
        return ($ip & $mask) == $subnet;
    }

    public static function isAcceptableNetwork($cidr)
    {
        $current_ip = request()->ip();

        if(self::cidr_match($current_ip, $cidr)) {
            return true;
        } else {
            return false;
        }
    }

    public static function mix2($path, $manifestDirectory = '')
    {
        static $manifest;

        if (! starts_with($path, '/')) {
            $path = "/{$path}";
        }
        if ($manifestDirectory && ! starts_with($manifestDirectory, '/')) {
            $manifestDirectory = "/{$manifestDirectory}";
        }
        if (file_exists(public_path($manifestDirectory.'/hot'))) {
            return new HtmlString("//localhost:8080{$path}");
        }
        if (! $manifest) {
            if (! file_exists($manifestPath = public_path($manifestDirectory.'/mix-manifest.json'))) {
                throw new Exception('The Mix manifest does not exist.');
            }
            $manifest = json_decode(file_get_contents($manifestPath), true);
        }
        if (! array_key_exists($path, $manifest)) {
            throw new Exception(
                "Unable to locate Mix file: {$path}. Please check your ".
                'webpack.mix.js output paths and try again.'
            );
        }
    }

    public static function auditGroups(User $user, $groups = [])
    {
        $global_administrator_group = env('GLOBAL_ADMINS_GROUP');

        foreach($groups as $group) {
            $group_record = DepartmentPermissionGroup::where('ldap_group_dn', $group)->first();
            if($group_record) {
                // add new access where there is none
                $permission_already_assigned = DepartmentUser::where('user_id', $user->id)->where('department_id', $group_record->department_id)->where('permission_id', $group_record->permission_id)->first();
                if(!$permission_already_assigned) {
                    $new_permission = new DepartmentUser;
                    $new_permission->user_id = $user->id;
                    $new_permission->department_id = $group_record->department_id;
                    $new_permission->permission_id = $group_record->permission_id;
                    $new_permission->save();
                }
            }
        }

        // remove access where it is no longer needed
        $users_permissions = DepartmentUser::where('user_id', $user->id)->get();
        foreach($users_permissions as $up) {
            if(is_null($up->department_id)) {
                // let's check if the user needs to be removed from the global administrators group
                if(!in_array($global_administrator_group, $groups)) {
                    DepartmentUser::find($up->id)->delete();
                }
            } else {
                $dept_permission_group = DepartmentPermissionGroup::where('permission_id', $up->permission_id)->where('department_id', $up->department_id)->first();
                if($dept_permission_group) {
                    if(!in_array($dept_permission_group->ldap_group_dn, $groups)) {
                        DepartmentUser::where('user_id', $user->id)->where('permission_id', $up->permission_id)->where('department_id', $up->department_id)->delete();
                    }
                }
            }
        }
    }

    public static function auditGlobalAdmin(User $user, $global_administrator_group)
    {
        $global_administrator_group = Permission::where('slug', 'global_administrator')->first();

        $permission_already_assigned = DepartmentUser::where('user_id', $user->id)->whereNull('department_id')->where('permission_id', $global_administrator_group->id)->first();

        if(!$permission_already_assigned) {
            $new_permission = new DepartmentUser;
            $new_permission->user_id = $user->id;
            $new_permission->department_id = null;
            $new_permission->permission_id = $global_administrator_group->id;
            $new_permission->save();
        }
    }

    public static function message()
    {
        if(session()->has('message')) {
            $message_object = session()->get('message');
            $typeid = $message_object['typeid'];
            $message = $message_object['message'];
            $timeout = $message_object['timeout'];

            return "<div class='alert alert-$typeid risevision-alert' data-timeout='$timeout'>$message</div>";
        } else {
            return "";
        }
    }
}
