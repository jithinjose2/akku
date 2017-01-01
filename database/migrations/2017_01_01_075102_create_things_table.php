<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateThingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('things', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('module_id', 45)->nullable();
			$table->string('key', 45)->nullable();
			$table->integer('type')->nullable()->comment('1 Digital out
2 Digial IN
3 Anaglog Out
4 Analog In');
			$table->string('name', 45)->nullable();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('things');
	}

}
