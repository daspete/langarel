<?php

use Illuminate\Database\Migrations\Migration;

class Appuserformdatas extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('appuserformdatas', function($table) {

            $table->increments('id');
            $table->string("userUID", 30)->unique();
            
            $table->foreign("userUID")
            		->references("uid")
            		->on("appuser")
            		->on_delete("restrict")
            		->on_update("cascade");
            
            $table->string("gender", 6);
            $table->string("vorname",40);
            $table->string("nachname",40);

            $table->string("email",100)->unique();

            $table->string("adresse",150);
            $table->string("plz",12);
            $table->string("ort",150);
            $table->string("land",150);

            $table->boolean("newsletter");
            $table->boolean("postletter");
            $table->boolean("noletter");

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
		Schema::drop('appuserformdatas');
	}

}