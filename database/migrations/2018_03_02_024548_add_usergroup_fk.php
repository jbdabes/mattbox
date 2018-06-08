<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsergroupFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('usergroup');
            $table->integer('usergroup_id')->unsigned()->default('1')->after('name');
            $table->foreign('usergroup_id')->references('id')->on('usergroups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('usergroup')->default('1')->after('name');
            $table->dropForeign('usergroup_id');
            $table->dropColumn('usergroup_id');
        });
    }
}
