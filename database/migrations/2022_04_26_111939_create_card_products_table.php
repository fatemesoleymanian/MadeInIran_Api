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
        Schema::create('card_products', function (Blueprint $table) {
            $table->id();
            //            $table->foreignId('card_id')
//                ->constrained()
//                ->onUpdate('cascade')
//                ->onDelete('cascade');
            //            $table->foreignId('product_id')
//                ->constrained()
//                ->onUpdate('cascade')
//                ->onDelete('cascade');
            //            $table->foreignId('state_id')
//                ->constrained()
//                ->onUpdate('cascade')
//                ->onDelete('cascade');
            $table->integer('count')->default(1);
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
        Schema::dropIfExists('card_products');
    }
};
