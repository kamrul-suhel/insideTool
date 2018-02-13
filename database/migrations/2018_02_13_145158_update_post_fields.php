<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePostFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->renameColumn('content', 'message');
            $table->string('name', 256);
            $table->string('link', 256);
            $table->string('picture', 256);
            $table->string('type', 28);
            $table->dateTime('posted');
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
            $table->renameColumn('message', 'content');
            $table->dropColumn('name');
            $table->dropColumn('link');
            $table->dropColumn('picture');
            $table->dropColumn('type');
            $table->dropColumn('posted');
        });
    }
}
