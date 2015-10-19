<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCycleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('cycle', function(Blueprint $table)
        {
            $table->increments('id');
            $table->date('start_date'); // username
            $table->date('end_date'); // username
            $table->timestamps();
        });
        // here i will make the reference for the users the role_id here
        Schema::table('fridays', function(Blueprint $table)
        {
            $table->foreign("cycle_id")->references("id")->on("cycle")->onDelete("cascade");
        });

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('fridays', function(Blueprint $table) {
            $table->dropForeign("fridays_cycle_id_foreign");
        });

        Schema::dropIfExists('cycle');
	}

}
