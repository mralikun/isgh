<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
        $this->call('RolesTableSeeder');

        $this->command->info('Roles table seeded!');
	}

}

class RolesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('roles')->delete();

        \App\Roles::create(['role_name' => 'admin' , "id"=>1]);
        \App\Roles::create(['role_name' => 'khateeb' , "id"=>2]);
        \App\Roles::create(['role_name' => 'ad' , "id"=>3]);

        DB::table('admin')->delete();
        \App\Admin::create(['id' => 1 , "name"=>"Eng Ahmed Ali", "email"=>"emineme32@yahoo.com"]);

        DB::table('users')->delete();
        $password = Hash::make("ahmedali32");
        \App\User::create(['username' => 'ahmedali' , "id"=>1, "user_id"=>1, "role_id"=>1 , "password"=>$password]);
    }

}