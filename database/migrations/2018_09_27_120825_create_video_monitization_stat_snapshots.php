<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoMonitizationStatSnapshots extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_monitization_stat_snapshots', function (Blueprint $table) {
            $table->increments('id');
            $table->string('post_id');
            $table->integer('post_video_ad_break_ad_impressions')->nullable();
            $table->integer('post_video_ad_break_earnings')->nullable();
            $table->integer('post_video_ad_break_ad_cpm')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::dropIfExists('video_monitization_stat_snapshots');
    }
}
