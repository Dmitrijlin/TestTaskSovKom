<?php

use Faker\Generator as Faker;


class UserTableSeeder extends \Illuminate\Database\Seeder
{
    public function run() {

        $user = [
            'last_name' => 'ADMIN',
            'name' => 'ADMIN',
            'patronymic' => '',
            'birth_day' => new DateTime(),
            'remember_token' => '4412a75db2ca0a2247f291d68c3da19f',
        ];

        $db = DB::table('users')->insert($user);
    }
}
