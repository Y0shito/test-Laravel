<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Bookmarks extends Migration
{
    public function up()
    {
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('article_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookmarks');
    }
}
