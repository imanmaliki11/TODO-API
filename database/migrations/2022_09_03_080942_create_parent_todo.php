<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParentTodo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parent_todo', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->unsignedBigInteger("user_id")->index();
            $table->boolean("is_public")->default(true);
            $table->timestamps();

            $table->foreign("user_id")->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parent_todo');
    }
}
