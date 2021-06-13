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
            $table->string('type', 7);
            $table->string('subject', 254)->nullable();
            $table->string('userid', 15);
            $table->string('username', 254);
            $table->boolean('usernew')->default(false);
            $table->string('email', 254);
            $table->string('grp', 254);
            $table->datetime('dated');
            $table->string('flags', 7);
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
