<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('milestone_id')->unsigned();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('extra')->nullable();
            $table->string('media')->nullable();
            $table->boolean('status')->default(1);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('milestone_id')->references('id')->on('milestones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
