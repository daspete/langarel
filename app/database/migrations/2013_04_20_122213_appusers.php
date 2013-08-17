<?php

use Illuminate\Database\Migrations\Migration;

class Appusers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('appuser', function($table) {
            $table->increments('id');
            $table->string("uid", 30)->unique();
            $table->string("vorname",60);
            $table->string("nachname",60);
            $table->string("nickname",120);
            $table->string("email",150)->unique();
            $table->string("gender",6);
			$table->boolean("isActive");
            $table->integer("visits");
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
		Schema::drop('appuser');
	}
}