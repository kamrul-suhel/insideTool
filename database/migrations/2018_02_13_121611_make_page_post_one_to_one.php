<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakePagePostOneToOne extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('page_posts');
        Schema::table('posts', function (Blueprint $table) {
            $table->bigInteger('facebook_id')->change();
            $table->integer('page_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::create('pages_posts', function (Blueprint $table) {
//            $table->increments('id');
//            $table->bigInteger('page_id');
//            $table->bigInteger('post_id');
//            $table->text('description');
//        });
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedInteger('facebook_id')->change();
            $table->dropColumn('page_id');
        });
    }
}
