<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
     Schema::create('user_activities', function (Blueprint $table) {
            $table->id();
        	$table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        	$table->unsignedBigInteger('activity_id');
            $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
        	$table->unsignedBigInteger('level_id');
            $table->foreign('level_id')->references('id')->on('levels')->onDelete('cascade');
            $table->unsignedBigInteger('level_option_id');
            $table->foreign('level_option_id')->references('id')->on('level_options')->onDelete('cascade');
            $table->softDeletes();
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
        Schema::dropIfExists('user_activities');
    }
}
