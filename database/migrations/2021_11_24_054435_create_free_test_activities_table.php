<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFreeTestActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('free_test_activities', function (Blueprint $table) {
            $table->id();
            $table->string('mobile');
            $table->string('email');
            $table->string('age');
            $table->string('name');
            $table->string('result');
            $tbale->softDeletes();
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
        Schema::dropIfExists('free_test_activities');
    }
}
