<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('path');
            $table->string('type');
            $table->integer('parent')->nullable()->unsigned();
            $table->integer('folder')->unsigned();
            $table->integer('size')->nullable();
            $table->string('mime')->nullable();
            $table->timestamps();

            $table->foreign('parent')
                ->references('id')->on('files')
                ->onDelete('cascade');

            $table->foreign('folder')
                ->references('id')->on('folders')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
