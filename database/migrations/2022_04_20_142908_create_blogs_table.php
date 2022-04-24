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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('post');
            $table->string('post_excerpt');
            $table->string('slug')->unique();
            $table->string('featuredImage');
            $table->string('metaDescription');
            $table->string('metaKeyword');
            $table->string('pageTitle');
            $table->integer('views')->default(0);
//            $table->foreignId('blog_category_id')
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
        Schema::dropIfExists('blogs');
    }
};
