<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tracking_number')->unique();
            $table->string('company_name');
            $table->foreignId('receiver_id')->nullable()->constrained('users');
            $table->string('receiver_email')->nullable();
            $table->string('receiver_street')->nullable();
            $table->string('receiver_city')->nullable();
            $table->integer('receiver_house_number')->nullable();
            $table->char('receiver_house_number_suffix', 3)->nullable();
            $table->foreignId('shipping_method_id')->constrained('shipping_methods');
            $table->integer('weight_grams');
            $table->integer('shipping_cost_cents');
            $table->string('current_status');
            $table->string('destination_province');
            $table->string('destination_street');
            $table->integer('house_number');
            $table->char('house_number_suffix', 3)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
