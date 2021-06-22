<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowTable extends Migration
{
    public function up()
    {
        Schema::create('follow', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('follow_id');
            $table->dateTime('created_at');

            //組み合わせのダブリ防止
            $table->unique(['user_id', 'follow_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('follow');
    }
}
