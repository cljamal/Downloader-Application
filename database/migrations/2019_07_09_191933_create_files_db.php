<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesDb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files_db', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('original_file_name')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_storage')->nullable();
            $table->string('file_url');
            $table->string('file_status')->nullable();
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
        Schema::dropIfExists('files_db');
    }
}
