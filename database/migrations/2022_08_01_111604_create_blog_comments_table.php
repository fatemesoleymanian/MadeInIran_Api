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
        Schema::create('blog_comments', function (Blueprint $table) {
            $table->id();
      //      $table->foreignId('user_id')
//                ->constrained()
//                ->onUpdate('cascade')
//                ->onDelete('cascade');
//            $table->foreignId('blog_id')
//                ->constrained()
//                ->onUpdate('cascade')
//                ->onDelete('cascade');
            $table->text('comment');
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('blog_comments');
    }
};
