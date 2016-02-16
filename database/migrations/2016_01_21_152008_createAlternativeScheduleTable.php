<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlternativeScheduleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('schedule', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('friday_id')->unsigned()->index();
            $table->integer('ic_id')->unsigned()->index();
            $table->integer('khateeb_id')->unsigned()->index();
            $table->integer('cycle_id')->unsigned()->index();
            $table->timestamps();
        });

        Schema::table('schedule', function(Blueprint $table)
        {
            $table->foreign("ic_id")->references("id")->on("islamic_center")->onDelete("cascade");
            $table->foreign("friday_id")->references("id")->on("fridays")->onDelete("cascade");
            $table->foreign("khateeb_id")->references("id")->on("users")->onDelete("cascade");
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
        Schema::dropIfExists("schedule");
	}

}
