<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public function ldapGroup($department_id)
    {
        $group = DepartmentPermissionGroup::where('department_id', $department_id)->where('permission_id', $this->id)->first();
        if($group) {
            return $group->ldap_group_dn;
        } else {
            return "";
        }
    }
}
