<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAttachmentsTable extends Migration
{

  public function up()
  {
    Schema::create('attachments', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name');
      $table->string('attachable_type');
      $table->integer('attachable_id')->unsigned();
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('attachments');
  }

}
