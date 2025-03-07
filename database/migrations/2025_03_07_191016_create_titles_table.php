<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTitlesTable extends Migration
{
    public function up()
    {
        Schema::create('titles', function (Blueprint $table) {
            $table->id();
            $table->string('activity');
            $table->string('name');
            $table->string('title_hash');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('titles');
    }
}
