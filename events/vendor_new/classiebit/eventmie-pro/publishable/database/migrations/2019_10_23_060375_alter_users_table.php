<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->string('avatar')->nullable()->default('users/default.png');
            $table->text('settings', 65535)->nullable();
			$table->string('Experience')->nullable()->default('Experience');
			$table->string('country')->nullable()->default('country');
			$table->string('state')->nullable()->default('state');
			$table->string('trip')->nullable()->default('trip');
			$table->string('city')->nullable()->default('city');
			$table->integer('role_id')->unsigned()->default(3)->index('users_role_id_foreign');
			$table->string('organisation', 256)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
