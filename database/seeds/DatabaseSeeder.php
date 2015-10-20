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
    }

}