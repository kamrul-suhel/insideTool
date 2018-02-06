<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostStatSnapshot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_stat_snapshot', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id');
            $table->unsignedInteger('facebook_id');
            $table->unsignedInteger('impressions');
            $table->unsignedInteger('uniques');
            $table->unsignedInteger('impressions_paid');
            $table->unsignedInteger('uniques_paid');
            $table->unsignedInteger('fan_impressions');
            $table->unsignedInteger('fan_uniques');
            $table->unsignedInteger('fan_impressions_paid');
            $table->unsignedInteger('fan_uniques_paid');
            $table->unsignedInteger('fan_impressions_organic');
            $table->unsignedInteger('fan_uniques_organic');
            $table->unsignedInteger('impressions_viral');
            $table->unsignedInteger('uniques_viral');
            $table->unsignedInteger('impressions_nonviral');
            $table->unsignedInteger('uniques_nonviral');
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
        Schema::dropIfExists('post_stat_snapshot');
    }
}
