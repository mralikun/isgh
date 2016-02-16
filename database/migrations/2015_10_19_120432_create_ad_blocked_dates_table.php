<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdBlockedDatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('ad_blocked_dates', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('ic_id')->unsigned()->index(); // username
            $table->integer('friday_id')->unsigned()->index(); // username
            $table->integer('confirm'); // confirmation 1 = added by ad , 2 = accepted , 3 = rejected
            $table->string("visitor_name");
            $table->integer('cycle_id')->unsigned()->index(); // username
            $table->timestamps();
        });

        Schema::table('ad_blocked_dates', function(Blueprint $table)
        {
            $table->foreign("friday_id")->references("id")->on("fridays")->onDelete("cascade");
        });

        Schema::table('ad_blocked_dates', function(Blueprint $table)
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
        Schema::table('ad_blocked_dates', function(Blueprint $table) {
            $table->dropForeign("ad_blocked_dates_friday_id_foreign");
        });

        Schema::table('ad_blocked_dates', function(Blueprint $table) {
            $table->dropForeign("ad_blocked_dates_cycle_id_foreign");
        });

        Schema::dropIfExists("ad_blocked_dates");
	}

}
