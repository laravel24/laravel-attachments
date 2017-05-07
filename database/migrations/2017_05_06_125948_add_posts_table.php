<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPostsTable extends Migration
{

  public function up()
  {
    Schema::create('posts', function(Blueprint $table) {
      $table->increments('id');
      $table->string('name')->nullable();
      $table->longText('content');
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('posts');
  }

}
