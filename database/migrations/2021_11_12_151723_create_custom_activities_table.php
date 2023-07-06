<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_activities', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('milestone_id');
            $table->foreign('milestone_id')->references('id')->on('milestones')->onDelete('cascade');
            $table->unsignedBigInteger('activity_id');
            $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
            $table->string('day');

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
        Schema::dropIfExists('custom_activities');
    }
}
