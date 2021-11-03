<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyColumnRentalTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
      Schema::table('rentals', function (Blueprint $table) {
          $table->integer('facilities')->change();
          $table->dropColumn('date');
          $table->date('date_start');
          $table->date('date_end');
      });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('facilities', function (Blueprint $table) {
        $table->json('facilities')->change();
        $table->dateTime('date');
        $table->dropColumn('date_start');
        $table->dropColumn('date_end');
    });
  }
}
