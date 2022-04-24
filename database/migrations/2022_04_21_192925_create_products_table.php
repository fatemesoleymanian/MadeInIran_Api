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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('inventory')->nullable(true);
            $table->string('image');
            $table->string('description_excerpt');
            $table->text('description');
            $table->string('metaDescription');
            $table->string('metaKeyword');
            $table->string('pageTitle');
            //            $table->foreignId('category_id')
//                ->constrained()
//                ->onUpdate('cascade')
//                ->onDelete('cascade');
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
        Schema::dropIfExists('products');
    }
};
