<?php

use Illuminate\Database\Seeder;

class InsertBaseLdapAttributes extends Seeder
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
                "local_attribute" => "fname",
                "ldap_attribute" => "givenName",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "local_attribute" => "lname",
                "ldap_attribute" => "sn",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "local_attribute" => "email",
                "ldap_attribute" => "mail",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "local_attribute" => "username",
                "ldap_attribute" => "ualrAlternateUID",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "local_attribute" => "groups",
                "ldap_attribute" => "isMemberOf",
                "created_at" => now(),
                "updated_at" => now()
            ],
        ];

        DB::table('ldap_attributes')->insert($data);
    }
}
