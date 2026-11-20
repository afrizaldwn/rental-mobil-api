<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 100)->unique();
            $table->string('phone', 20);
            $table->text('address');
            $table->enum('gender', ['male', 'female']);
            $table->date('birth_date');
            $table->string('id_card_number', 16)->unique();
            $table->string('driver_license_number', 20)->unique();
            $table->string('city', 50);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
};