<?php

use Illuminate\Database\Seeder;

class UserGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('usergroups')->insert([
             'id' => 1,
            'title' => 'User',
        ]);
    }
}
