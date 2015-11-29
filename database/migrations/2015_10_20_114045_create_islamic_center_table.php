<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIslamicCenterTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

        Schema::create('islamic_center', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name')->unique()->index();

            $table->integer('director_id')->unsigned()->index();
            $table->timestamp('khutbah_start');
            $table->timestamp('khutbah_end');

            $table->string('other_information');
            $table->string('website');
            $table->string('parking_information');

            $table->string('country');
            $table->string('city');
            $table->string('address');
            $table->integer('postal_code');
            $table->string('state');
            $table->integer('speech_num');

            $table->timestamps();
        });

        Schema::table('islamic_center', function(Blueprint $table)
        {
            $table->foreign("director_id")->references("id")->on("associate_director")->onDelete("cascade");
        });
        // this section related to the ad blocked dates
        Schema::table('ad_blocked_dates', function(Blueprint $table)
        {
            $table->foreign("ic_id")->references("id")->on("islamic_center")->onDelete("cascade");
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('islamic_center', function(Blueprint $table) {
            $table->dropForeign("islamic_center_director_id_foreign");
        });

         // this section related to the ad blocked dates
        Schema::table('ad_blocked_dates', function(Blueprint $table) {
            $table->dropForeign("ad_blocked_dates_ic_id_foreign");
        });

        Schema::dropIfExists("islamic_center");
	}

}
