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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable();
            $table->string('phone_number',11)->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->text('address')->nullable();
            $table->rememberToken();
            $table->bigInteger('role_id',false,true);
            //            $table->foreignId('role_id')
//                ->constrained()
//                ->onUpdate('cascade')
//                ->onDelete('cascade');
            $table->timestamp('email_verified_at')->nullable();
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
        Schema::dropIfExists('admins');
    }
};
