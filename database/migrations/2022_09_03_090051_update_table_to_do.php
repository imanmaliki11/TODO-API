<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTableToDo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('to_do', function (Blueprint $table) {
            $table->integer("progress")->default(0)->change();
            $table->timestamp("due_date")->nullable()->change()->default(null);
            $table->string("note")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('to_do', function (Blueprint $table) {
            //
        });
    }
}
