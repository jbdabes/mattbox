<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrivateColumnToShouts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shouts', function (Blueprint $table) {
            $table->integer('private')->unsigned()->nullable()->after('sys');
            $table->foreign('private')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shouts', function (Blueprint $table) {
            $table->dropForeign('private');
            $table->dropColumn('private');
        });
    }
}
