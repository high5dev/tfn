<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('statd')->default(0);
            $table->datetime('started');
            $table->datetime('stopped')->nullable();
            $table->bigInteger('startid')->unsigned();
            $table->datetime('startts');
            $table->bigInteger('stopid')->unsigned()->default(0);
            $table->datetime('stopts')->nullable();
            $table->integer('zaps')->unsigned()->default(0);
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scans');
    }
}
