<?php

use Illuminate\Database\Seeder;

class InsertGlobalAdministratorsGroup extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                "department_id" => "-1",
                "permission_id" => "-1",
                "ldap_group_dn" => "cn=global-administrators,ou=groups,o=risevision,o=apps,dc=ualr,dc=edu",
                "created_at" => now(),
                "updated_at" => now()
            ],
        ];
        DB::table()->insert($data);
    }
}
