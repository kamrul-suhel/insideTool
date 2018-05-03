<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultValuesForDelayed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('post_delayed_stat_snapshots', function (Blueprint $table) {
            $table->unsignedInteger('impressions')->default(0)->change();
            $table->unsignedInteger('uniques')->default(0)->change();
            $table->unsignedInteger('impressions_paid')->default(0)->change();
            $table->unsignedInteger('uniques_paid')->default(0)->change();
            $table->unsignedInteger('impressions_viral')->default(0)->change();
            $table->unsignedInteger('uniques_viral')->default(0)->change();
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
            $table->unsignedInteger('impressions')->default(null)->change();
            $table->unsignedInteger('uniques')->default(null)->change();
            $table->unsignedInteger('impressions_paid')->default(null)->change();
            $table->unsignedInteger('uniques_paid')->default(null)->change();
            $table->unsignedInteger('impressions_viral')->default(null)->change();
            $table->unsignedInteger('uniques_viral')->default(null)->change();
        });
    }
}
