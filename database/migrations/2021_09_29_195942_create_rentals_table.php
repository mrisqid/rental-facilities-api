<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('name');
            $table->string('identity_card');
            $table->string('phone_number');
            $table->string('organization_name');
            $table->string('organization_address');
            $table->string('organization_image');
            $table->integer('facilities');
            $table->string('message');
            $table->string('file');
            $table->date('date_start');
            $table->date('date_end');
            $table->integer('status')->default(0);
            $table->string('file_approve')->nullable();
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
        Schema::dropIfExists('rentals');
    }
}
