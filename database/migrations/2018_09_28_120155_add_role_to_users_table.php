<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRoleToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'user'])->default('user')->after('email');
        });

        $newUser = new \App\User();
        $newUser->name = 'Admin';
        $newUser->email = 'admin@unilad.co.uk';
        $newUser->password = bcrypt('dfghgtfDETYGFertygf');
        $newUser->role = 'admin';
        $newUser->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
	    $adminUser = \App\User::where('email', 'admin@unilad.co.uk')->get()->delete();
    }
}
