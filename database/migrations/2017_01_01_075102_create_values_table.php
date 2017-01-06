<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateValuesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('values', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('thing_id')->nullable();
			$table->integer('value')->nullable();
			$table->timestamps();
			$table->string('value_str', 45)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('values');
	}

}
