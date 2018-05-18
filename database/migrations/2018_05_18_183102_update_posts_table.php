<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedInteger('ga_page_views')->default(0);
            $table->unsignedInteger('ga_avg_time_on_page')->default(0);
            $table->unsignedInteger('ga_bounce_rate')->default(0);
            $table->unsignedInteger('ga_avg_page_load_time')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('ga_page_views');
            $table->dropColumn('ga_avg_time_on_page');
            $table->dropColumn('ga_bounce_rate');
            $table->dropColumn('ga_avg_page_load_time');
        });
    }
}
