<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_packages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('package_id')->unsigned();
            $table->bigInteger('order_id')->unsigned();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->string('status')->nullable();
            $table->softDeletes();
            $table->timestamps();




            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('package_id')->references('id')->on('packages');
            $table->foreign('order_id')->references('id')->on('orders');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_packages');
    }
}
