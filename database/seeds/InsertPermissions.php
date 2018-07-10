<?php

use Illuminate\Database\Seeder;

class InsertPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            [
                "name" => "Global Administrator",
                "slug" => "global_administrator",
                "description" => "Global administrators can manage and view all content across all Departments. They can also create, update, and delete Departments.",
                "level" => "10000",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Department Administrator",
                "slug" => "department_administrator",
                "description" => "Department administrators can manage and view all content in their assigned Department(s). They can also create and update their assigned Departments as well as add and remove users.",
                "level" => "5000",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Department User",
                "slug" => "department_user",
                "description" => "Department users can access and view all content in their assigned Department(s), but they cannot add/upload new content.",
                "level" => "1000",
                "created_at" => now(),
                "updated_at" => now()
            ],
        ]);
    }
}
