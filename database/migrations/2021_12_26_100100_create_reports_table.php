<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->biginteger('user_id')->unsigned();
            $table->biginteger('member_id')->unsigned();
            $table->string('title');
            $table->text('justification');
            $table->string('found');
            $table->string('warnings');
            $table->text('warning_emails');
            $table->text('body');
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
        Schema::dropIfExists('reports');
    }
}
