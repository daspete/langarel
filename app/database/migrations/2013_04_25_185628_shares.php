<?php

use Illuminate\Database\Migrations\Migration;

class Shares extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('shares', function($table) {

            $table->increments('id');
            $table->string("userUID", 30);
            $table->string("sharePoint", 50);
            $table->boolean("wasShared");
			$table->string("response", 150);

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
		Schema::drop('shares');
	}

}