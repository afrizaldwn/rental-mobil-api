<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('car_brand', 50);
            $table->string('car_model', 50);
            $table->string('plate_number', 15);
            $table->date('rental_date');
            $table->date('return_date');
            $table->unsignedBigInteger('total_price');
            $table->enum('status', ['booked', 'ongoing', 'returned', 'cancelled'])->default('booked');
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rentals');
    }
};