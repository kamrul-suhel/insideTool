<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultValuesToMetrics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->bigInteger('likes')->default(0)->change();
            $table->bigInteger('comments')->default(0)->change();
            $table->bigInteger('shares')->default(0)->change();
            $table->bigInteger('reach')->default(0)->change();
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
            $table->bigInteger('likes')->default(null)->change();
            $table->bigInteger('comments')->default(null)->change();
            $table->bigInteger('shares')->default(null)->change();
            $table->bigInteger('reach')->default(null)->change();
        });
    }
}
