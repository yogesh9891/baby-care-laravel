<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('favcy_user_id')->unsigned();
            $table->bigInteger('package_id')->unsigned();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('sub_total')->nullable();
            $table->string('coupon_id')->nullable();
            $table->string('discount')->nullable();
            $table->string('total')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('currency')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
