<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTramitesCampoTramitesTable.
 */
class CreateTramitesCampoTramitesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tramites_campo_tramites', function(Blueprint $table) {
            $table->increments('id');
						$table->bigInteger('id_tramite');
						$table->bigInteger('id_campo');
						$table->bigInteger('id_tipo');
						$table->string('caracteristicas',255);
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
		Schema::drop('tramites_campo_tramites');
	}
}
