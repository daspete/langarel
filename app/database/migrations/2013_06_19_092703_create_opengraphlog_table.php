<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOpengraphlogTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opengraphLog', function(Blueprint $table) {
            $table->increments('id');
            $table->string("action",50);
            $table->string("fromUID",30);
            $table->string("graphID",80);

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
        Schema::drop('opengraphLog');
    }

}
