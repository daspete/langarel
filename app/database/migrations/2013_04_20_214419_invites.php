<?php

use Illuminate\Database\Migrations\Migration;

class Invites extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('invites', function($table) {
            $table->increments('id');
            $table->string("fromUID", 30)->index();
			
            $table->foreign("fromUID")
            		->references("uid")
            		->on("appuser")
            		->onUpdate("cascade")
            		->onDelete("cascade");

			$table->string("toUID", 30)->index();
			$table->string("requestID", 60);

            $table->foreign("toUID")
            		->references("uid")
            		->on("appuser")
            		->onUpdate("cascade")
            		->onDelete("restrict");

			$table->boolean("accepted");
			$table->boolean("deleted");

			$table->timestamp("deletedAt")->index();
			$table->timestamp("acceptedAt")->index();

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
		Schema::drop('invites');
	}

}