<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteDelayedFieldsFromStatSnapshots extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('post_stat_snapshots', function (Blueprint $table) {
            $table->dropColumn('impressions');
            $table->dropColumn('uniques');
            $table->dropColumn('impressions_paid');
            $table->dropColumn('uniques_paid');
            $table->dropColumn('fan_impressions');
            $table->dropColumn('fan_uniques');
            $table->dropColumn('fan_impressions_paid');
            $table->dropColumn('fan_uniques_paid');
            $table->dropColumn('impressions_organic');
            $table->dropColumn('uniques_organic');
            $table->dropColumn('impressions_viral');
            $table->dropColumn('uniques_viral');
            $table->dropColumn('impressions_nonviral');
            $table->dropColumn('uniques_nonviral');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post_stat_snapshots', function (Blueprint $table) {
            $table->unsignedInteger('impressions');
            $table->unsignedInteger('uniques');
            $table->unsignedInteger('impressions_paid');
            $table->unsignedInteger('uniques_paid');
            $table->unsignedInteger('fan_impressions');
            $table->unsignedInteger('fan_uniques');
            $table->unsignedInteger('fan_impressions_paid');
            $table->unsignedInteger('fan_uniques_paid');
            $table->unsignedInteger('impressions_organic');
            $table->unsignedInteger('uniques_organic');
            $table->unsignedInteger('impressions_viral');
            $table->unsignedInteger('uniques_viral');
            $table->unsignedInteger('impressions_nonviral');
            $table->unsignedInteger('uniques_nonviral');
        });
    }
}
