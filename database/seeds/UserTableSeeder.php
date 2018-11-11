<?php

use Carbon\Carbon;

class UserTableSeeder extends DatabaseSeeder
{
    public function run()
    {
        $now = Carbon::now();

        DB::table('user_types')->insert([
            [
                'description' => 'Agent',
            ],
            [
                'description' => 'Buyer',
            ]
        ]);

        DB::table('users')->insert([
            [
                'first_name' => 'Agent',
                'last_name' => 'Tester',
                'email' => 'agent@test.com',
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'user_type_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'first_name' => 'Buyer',
                'last_name' => 'Tester',
                'email' => 'buyer@test.com',
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'user_type_id' => 2,
                'created_at' => $now,
                'updated_at' => $now, 
            ]
        ]);

        DB::table('user_dependency_permissions')->insert([
            [
                'user_dependency_type_id' => 1,
                'user_id' => 1
            ],
            [
                'user_dependency_type_id' => 2,
                'user_id' => 2
            ]
        ]);

        DB::table('user_dependency_types')->insert([
            [
                'description' => 'Artist',
            ],
            [
                'description' => 'Venue',
            ]
        ]);

        DB::table('user_dependencies')->insert([
            [
                'name' => 'Wholly Molies',
                'user_id' => 1,
                'user_dependency_type_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Cool Cat and the Copies',
                'user_id' => 1,
                'user_dependency_type_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Thunderdome',
                'user_id' => 2,
                'user_dependency_type_id' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Flavian Amphitheatre',
                'user_id' => 2,
                'user_dependency_type_id' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);
    }
}