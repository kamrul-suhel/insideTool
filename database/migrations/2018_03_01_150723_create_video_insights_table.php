<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoInsightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_stat_snapshots', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id');
            $table->unsignedInteger('total_video_views')->default(0);
            $table->unsignedInteger('total_video_views_unique')->default(0);
            $table->unsignedInteger('total_video_views_autoplayed')->default(0);
            $table->unsignedInteger('total_video_views_clicked_to_play')->default(0);
            $table->unsignedInteger('total_video_views_organic')->default(0);
            $table->unsignedInteger('total_video_views_organic_unique')->default(0);
            $table->unsignedInteger('total_video_views_paid')->default(0);
            $table->unsignedInteger('total_video_views_paid_unique')->default(0);
            $table->unsignedInteger('total_video_views_sound_on')->default(0);
            $table->unsignedInteger('total_video_complete_views')->default(0);
            $table->unsignedInteger('total_video_complete_views_unique')->default(0);
            $table->unsignedInteger('total_video_complete_views_autoplayed')->default(0);
            $table->unsignedInteger('total_video_complete_views_clicked_to_play')->default(0);
            $table->unsignedInteger('total_video_complete_views_organic')->default(0);
            $table->unsignedInteger('total_video_complete_views_organic_unique')->default(0);
            $table->unsignedInteger('total_video_complete_views_paid')->default(0);
            $table->unsignedInteger('total_video_complete_views_paid_unique')->default(0);
            $table->unsignedInteger('total_video_10s_views')->default(0);
            $table->unsignedInteger('total_video_10s_views_unique')->default(0);
            $table->unsignedInteger('total_video_10s_views_auto_played')->default(0);
            $table->unsignedInteger('total_video_10s_views_clicked_to_play')->default(0);
            $table->unsignedInteger('total_video_10s_views_organic')->default(0);
            $table->unsignedInteger('total_video_10s_views_paid')->default(0);
            $table->unsignedInteger('total_video_10s_views_sound_on')->default(0);
            $table->unsignedInteger('total_video_avg_time_watched')->default(0);
            $table->unsignedInteger('total_video_view_total_time')->default(0);
            $table->unsignedInteger('total_video_view_total_time_organic')->default(0);
            $table->unsignedInteger('total_video_view_total_time_paid')->default(0);
            $table->unsignedInteger('total_video_impressions')->default(0);
            $table->unsignedInteger('total_video_impressions_unique')->default(0);
            $table->unsignedInteger('total_video_impressions_paid_unique')->default(0);
            $table->unsignedInteger('total_video_impressions_paid')->default(0);
            $table->unsignedInteger('total_video_impressions_organic_unique')->default(0);
            $table->unsignedInteger('total_video_impressions_organic')->default(0);
            $table->unsignedInteger('total_video_impressions_viral_unique')->default(0);
            $table->unsignedInteger('total_video_impressions_viral')->default(0);
            $table->unsignedInteger('total_video_impressions_fan_unique')->default(0);
            $table->unsignedInteger('total_video_impressions_fan')->default(0);
            $table->unsignedInteger('total_video_impressions_fan_paid_unique')->default(0);
            $table->unsignedInteger('total_video_impressions_fan_paid')->default(0);
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
        Schema::dropIfExists('video_stat_snapshots');
    }
}
