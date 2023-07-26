<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTodo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('to_do', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent');
            $table->string("title");
            $table->integer("progress");
            $table->enum("status", ["done", "created", "progress"])->default("created");
            $table->timestamp("due_date");
            $table->string("note");
            $table->timestamps();

            $table->foreign("parent")->references('id')->on('parent_todo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_todo');
    }
}
