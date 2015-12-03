<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdChooseTheirIcTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('ad_choose_their_ic', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('ad_id')->unsigned()->index();
            $table->integer('friday_id')->unsigned()->index();
            $table->integer('cycle_id')->unsigned()->index();
            $table->timestamps();
        });

        Schema::table('ad_choose_their_ic', function(Blueprint $table)
        {
            $table->foreign("ad_id")->references("id")->on("associate_director")->onDelete("cascade");
            $table->foreign("friday_id")->references("id")->on("fridays")->onDelete("cascade");
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
        Schema::dropIfExists("ad_choose_their_ic");
	}

}
