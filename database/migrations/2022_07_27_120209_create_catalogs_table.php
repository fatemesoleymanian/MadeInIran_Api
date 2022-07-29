<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogs', function (Blueprint $table) {
            $table->id();
            $table->string('full_name',100);
            $table->string('phone_number',12);
            $table->string('city',50);
            $table->integer('age')->nullable(true);
            $table->string('education')->nullable(true);
            $table->string('course',50)->nullable(true);
            $table->string('work_experience',100)->nullable(true);
            $table->string('job',50)->nullable(true);
            $table->string('selected_package')->nullable(true);
            $table->string('reasons',200)->nullable(true);
            $table->string('experts')->nullable(true);
            $table->string('product');
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
        Schema::dropIfExists('catalogs');
    }
};
