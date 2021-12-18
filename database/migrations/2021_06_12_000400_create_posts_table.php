<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('member_id');
            $table->string('type', 7);
            $table->string('subject', 254)->nullable();
            $table->string('username', 254);
            $table->string('email', 254);
            $table->string('grp', 254);
            $table->datetime('dated');
            $table->string('status', 7);
            $table->boolean('usernew')->default(false);
            $table->boolean('spam')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
