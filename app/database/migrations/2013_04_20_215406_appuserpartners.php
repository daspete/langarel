<?php

use Illuminate\Database\Migrations\Migration;

class Appuserpartners extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('appuserpartners', function($table) {
            $table->increments('id');
            
            $table->string("userUID", 30);
            
            $table->foreign("userUID")
            		->references("uid")
            		->on("appuser")
            		->onDelete("cascade")
            		->onUpdate("cascade");

            $table->string("partnerUID", 30);

            $table->foreign("partnerUID")
            		->references("uid")
            		->on("appuser")
            		->onDelete("cascade")
            		->onUpdate("cascade");

			$table->integer("recipeID");

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
		Schema::drop('appuserpartners');
	}

}