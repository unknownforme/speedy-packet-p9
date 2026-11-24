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
            $table->string('tracking_number')->unique();
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('receiver_id')->nullable()->constrained('users');
            $table->foreignId('shipping_method_id')->constrained('shipping_methods');
            $table->integer('weight_grams');
            $table->integer('shipping_cost_cents');
            $table->string('current_status');
            $table->string('receiver_email');
            $table->string('destination_province');
            $table->string('destination_street');
            $table->integer('house_number');
            $table->char('house_number_suffix')->nullable();
            $table->foreignId('location_id')->nullable()->constrained('distribution_locations');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};