<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPricesColumnToPlans extends Migration
{
	/**
	* Run the migrations.
	*
	* @return void
	*/
	public function up()
	{
		Schema::table('plans', function (Blueprint $table) {
			$table->dropColumn('currency');
			$table->{$this->jsonable()}('price')->default(null)->change();
		});
	}

	/**
	* Reverse the migrations.
	*
	* @return void
	*/
	public function down()
	{
		Schema::table('plans', function (Blueprint $table) {
			$table->decimal('price', 8, 2)->default(0)->change();
			$table->string('currency', 3)->after('signup_fee');
		});
	}

	/**
	* Get jsonable column data type.
	*
	* @return string
	*/
	protected function jsonable(): string
	{
		$driverName = DB::connection()->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME);
		$dbVersion = DB::connection()->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION);
		$isOldVersion = version_compare($dbVersion, '5.7.8', 'lt');

		return $driverName === 'mysql' && $isOldVersion ? 'text' : 'json';
	}
}
