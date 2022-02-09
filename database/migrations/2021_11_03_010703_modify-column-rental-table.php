<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifyColumnRentalTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
      DB::statement('ALTER TABLE rentals ALTER COLUMN facilities TYPE integer USING (trim(facilities))::integer');
      Schema::table('rentals', function (Blueprint $table) {
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
