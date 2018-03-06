<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeTotalViewTimesBigints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('video_stat_snapshots', function (Blueprint $table) {
            $table->bigInteger('total_video_view_total_time')->change();
            $table->bigInteger('total_video_view_total_time_organic')->change();
            $table->bigInteger('total_video_view_total_time_paid')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('video_stat_snapshots', function (Blueprint $table) {
            $table->unsignedInteger('total_video_view_total_time')->change();
            $table->unsignedInteger('total_video_view_total_time_organic')->change();
            $table->unsignedInteger('total_video_view_total_time_paid')->change();
        });
    }
}
