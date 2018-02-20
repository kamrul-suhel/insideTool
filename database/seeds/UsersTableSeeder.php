<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'UNILAD Insights User',
            'email' => 'insights@unilad.co.uk',
            'password' => bcrypt('JJm399CqQQgUtmqg'),
        ]);
    }
}
