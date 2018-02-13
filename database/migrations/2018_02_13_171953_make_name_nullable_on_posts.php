<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeNameNullableOnPosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->string('link')->nullable()->change();
            $table->string('picture')->nullable()->change();
            $table->string('message')->nullable()->change();
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
            $table->string('name')->nullable(false)->change();
            $table->string('link')->nullable(false)->change();
            $table->string('picture')->nullable(false)->change();
            $table->string('message')->nullable(false)->change();
        });
    }
}
