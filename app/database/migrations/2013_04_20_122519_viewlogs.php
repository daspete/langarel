<?php

use Illuminate\Database\Migrations\Migration;

class Viewlogs extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('viewlog', function($table) {
            $table->increments('id');
            $table->string("uid", 30);
            $table->string("context", 50);
	        $table->string("ip",15);
            $table->text("browser");
            $table->text("request");
            $table->string("queryString",255);
            $table->text("referrer");

            $table->timestamps();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
		Schema::drop('viewlog');
	}

}