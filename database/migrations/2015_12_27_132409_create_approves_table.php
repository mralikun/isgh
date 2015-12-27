<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApprovesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('approves', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('cycle_id')->unsigned()->index();
            $table->integer("approve");
			$table->timestamps();
		});

        Schema::table('approves', function(Blueprint $table)
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
		Schema::drop('approves');
	}

}
