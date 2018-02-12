<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixOrganicFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('post_stat_snapshot', 'post_stat_snapshots');
        Schema::table('post_stat_snapshots', function($table)
        {
            $table->bigInteger('facebook_id')->change();
            $table->renameColumn('fan_impressions_organic', 'impressions_organic');
            $table->renameColumn('fan_uniques_organic', 'uniques_organic');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('post_stat_snapshots', 'post_stat_snapshot');
        Schema::table('post_stat_snapshot', function($table)
        {
            $table->integer('facebook_id')->change();
            $table->renameColumn('impressions_organic', 'fan_impressions_organic');
            $table->renameColumn('uniques_organic', 'fan_uniques_organic');
        });
    }
}
