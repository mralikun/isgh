<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdKhateebsPhotoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('ad_khateebs_photo', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('ad_id')->unsigned()->index();
            $table->string('photo_url');
            $table->timestamps();
        });

        Schema::table('ad_khateebs_photo', function(Blueprint $table)
        {
            $table->foreign("ad_id")->references("id")->on("associate_director")->onDelete("cascade");
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::dropIfExists("ad_khateebs_photo");
	}

}
