<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShoutStyleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shout_style', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('color')->nullable();
            $table->string('font')->nullable();
            $table->boolean('bold')->default(0);
            $table->boolean('italic')->default(0);
            $table->boolean('underline')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shout_style');
    }
}
