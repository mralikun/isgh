<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKhateebselectedfridaysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('Khateebselectedfridays', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('khateeb_id')->unsigned()->index(); // username
            $table->integer('role_id'); // role id
            $table->integer('friday_id')->unsigned()->index(); // username
            $table->integer('cycle_id')->unsigned()->index(); // username
            $table->timestamps();
        });

        Schema::table('Khateebselectedfridays', function(Blueprint $table)
        {
            $table->foreign("khateeb_id")->references("id")->on("users")->onDelete("cascade");
        });

        Schema::table('Khateebselectedfridays', function(Blueprint $table)
        {
            $table->foreign("friday_id")->references("id")->on("fridays")->onDelete("cascade");
        });

        Schema::table('Khateebselectedfridays', function(Blueprint $table)
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
        Schema::table('Khateebselectedfridays', function(Blueprint $table) {
            $table->dropForeign("Khateebselectedfridays_khateeb_id_foreign");
        });

		Schema::table('Khateebselectedfridays', function(Blueprint $table) {
            $table->dropForeign("Khateebselectedfridays_friday_id_foreign");
        });

		Schema::table('Khateebselectedfridays', function(Blueprint $table) {
            $table->dropForeign("Khateebselectedfridays_cycle_id_foreign");
        });

		Schema::dropIfExists("Khateebselectedfridays");
	}

}
