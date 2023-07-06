<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('activity_id')->unsigned();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('media')->nullable();
            $table->string('extra')->nullable();
            $table->boolean('status')->default(1);
            $table->softDeletes();
            $table->timestamps();


            $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('levels');
    }
}
