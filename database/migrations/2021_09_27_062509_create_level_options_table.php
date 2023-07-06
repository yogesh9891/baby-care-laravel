<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLevelOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('level_options', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('level_id')->unsigned();
            $table->string('option_text')->nullable();
            $table->string('points')->nullable();
            $table->string('extra')->nullable();
            $table->softDeletes();
            $table->timestamps();


            $table->foreign('level_id')->references('id')->on('levels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('level_options');
    }
}
