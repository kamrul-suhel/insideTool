<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVideoStatSnapshotsFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('video_stat_snapshots', function (Blueprint $table) {
            $table->text('total_video_retention_graph')->after('total_video_impressions_fan_paid')->nullable();
            $table->text('total_video_retention_graph_autoplayed')->after('total_video_impressions_fan_paid')->nullable();
            $table->text('total_video_retention_graph_clicked_to_play')->after('total_video_impressions_fan_paid')->nullable();
            $table->text('total_video_view_time_by_age_bucket_and_gender')->after('total_video_impressions_fan_paid')->nullable();
            $table->text('total_video_view_time_by_region_id')->after('total_video_impressions_fan_paid')->nullable();
            $table->text('total_video_reactions_by_type_total')->after('total_video_impressions_fan_paid')->nullable();
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
	        $table->dropColumn('total_video_retention_graph');
	        $table->dropColumn('total_video_retention_graph_autoplayed');
	        $table->dropColumn('total_video_retention_graph_clicked_to_play');
	        $table->dropColumn('total_video_view_time_by_age_bucket_and_gender');
	        $table->dropColumn('total_video_view_time_by_region_id');
	        $table->dropColumn('total_video_reactions_by_type_total');
        });
    }
}
