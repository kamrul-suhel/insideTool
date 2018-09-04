<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropFanFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('post_delayed_stat_snapshots', function (Blueprint $table) {
            $table->dropColumn('fan_impressions');
            $table->dropColumn('fan_uniques');
            $table->unsignedInteger('impressions_paid');
            $table->unsignedInteger('uniques_paid');
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
			$table->unsignedInteger('fan_impressions');
			$table->unsignedInteger('fan_uniques');
			$table->dropColumn('impressions_paid');
			$table->dropColumn('uniques_paid');
		});
	}
}
