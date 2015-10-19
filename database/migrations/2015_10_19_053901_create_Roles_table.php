<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{


        Schema::create('roles', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('role_name')->unique(); // username
            $table->timestamps();
        });

        // here i will make the reference for the users the role_id here
        Schema::table('users', function(Blueprint $table)
        {
            $table->foreign("role_id")->references("id")->on("roles")->onDelete("cascade");
        });
	}




	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('users', function(Blueprint $table) {
            $table->dropForeign("users_role_id_foreign");
        });

        Schema::dropIfExists('roles');


	}

}
