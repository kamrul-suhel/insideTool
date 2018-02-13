<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostDelayedStatSnapshots extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_delayed_stat_snapshots', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id');
            $table->bigInteger('facebook_id');
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
        Schema::dropIfExists('post_delayed_stat_snapshots');
    }
}
