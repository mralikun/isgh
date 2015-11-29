<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('rating', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('ad_id')->unsigned()->index();
            $table->integer('khateeb_id')->unsigned()->index();
            $table->integer('ad_rate_khateeb');
            $table->integer('khateeb_rate_ad');
            $table->integer('cycle_id')->unsigned()->index();
            $table->integer('distance');
            $table->timestamps();
        });

        Schema::table('rating', function(Blueprint $table)
        {
            $table->foreign("ad_id")->references("id")->on("associate_director")->onDelete("cascade");
        });

        Schema::table('rating', function(Blueprint $table)
        {
            $table->foreign("khateeb_id")->references("id")->on("khateeb")->onDelete("cascade");
        });

        Schema::table('rating', function(Blueprint $table)
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
        Schema::table('rating', function(Blueprint $table) {
            $table->dropForeign("rating_ad_id_foreign");
        });

        Schema::table('rating', function(Blueprint $table) {
            $table->dropForeign("rating_khateeb_id_foreign");
        });

        Schema::table('rating', function(Blueprint $table) {
            $table->dropForeign("rating_cycle_id_foreign");
        });

        Schema::dropIfExists("rating");
	}

}
