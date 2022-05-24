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
        Schema::create('request_for_representations', function (Blueprint $table) {
            $table->id();
            $table->string('full_name',50);
            $table->string('phone_number',12);
            $table->string('city',40);
            $table->integer('age')->nullable(true);
            $table->string('education')->nullable(true);
            $table->string('course',30)->nullable(true);
            $table->string('work_experience',50)->nullable(true);
            $table->string('job',50)->nullable(true);
            $table->string('selected_package')->nullable(true);
            $table->string('reasons',200)->nullable(true);
            $table->string('experts')->nullable(true);
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
        Schema::dropIfExists('request_for_representations');
    }
};
