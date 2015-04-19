<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNumberMessageInHelpsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('helps', function(Blueprint $table)
		{
			$table->string('name');
			$table->string('number');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('helps', function(Blueprint $table)
		{
			$table->dropColumn('name');
			$table->dropColumn('number');
		});
	}

}
