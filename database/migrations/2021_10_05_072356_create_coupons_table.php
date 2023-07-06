<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('coupon_code');
            $table->string('type');
            $table->string('value');
            $table->string('minimum_spend')->default(0);
            $table->string('maximum_spend')->default(0);
            $table->string('usage_limit_per_coupon')->nullable();
            $table->string('usage_limit_per_user')->nullable();
            $table->string('used')->nullable();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('coupons');
    }
}
