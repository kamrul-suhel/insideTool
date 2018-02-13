<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropPaidFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('post_delayed_stat_snapshots', function (Blueprint $table) {
            $table->dropColumn('impressions_paid');
            $table->dropColumn('uniques_paid');
            $table->dropColumn('fan_impressions_paid');
            $table->dropColumn('fan_uniques_paid');
            $table->dropColumn('impressions_organic');
            $table->dropColumn('uniques_organic');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post_delayed_stat_snapshots', function (Blueprint $table) {
            $table->unsignedInteger('impressions_paid');
            $table->unsignedInteger('uniques_paid');
            $table->unsignedInteger('fan_impressions_paid');
            $table->unsignedInteger('fan_uniques_paid');
            $table->unsignedInteger('impressions_organic');
            $table->unsignedInteger('uniques_organic');
        });
    }
}
