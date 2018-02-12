<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLikesSharesComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('post_stat_snapshots', function($table) {
            $table->unsignedInteger('comments')->default(0)->after('facebook_id');
            $table->unsignedInteger('shares')->default(0)->after('facebook_id');
            $table->unsignedInteger('thankfuls')->default(0)->after('facebook_id');
            $table->unsignedInteger('angrys')->default(0)->after('facebook_id');
            $table->unsignedInteger('sads')->default(0)->after('facebook_id');
            $table->unsignedInteger('hahas')->default(0)->after('facebook_id');
            $table->unsignedInteger('wows')->default(0)->after('facebook_id');
            $table->unsignedInteger('loves')->default(0)->after('facebook_id');
            $table->unsignedInteger('likes')->default(0)->after('facebook_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post_stat_snapshots', function($table) {
            $table->dropColumn('comments');
            $table->dropColumn('shares');
            $table->dropColumn('likes');
            $table->dropColumn('thankfuls');
            $table->dropColumn('angrys');
            $table->dropColumn('sads');
            $table->dropColumn('hahas');
            $table->dropColumn('wows');
            $table->dropColumn('loves');
        });
    }
}
