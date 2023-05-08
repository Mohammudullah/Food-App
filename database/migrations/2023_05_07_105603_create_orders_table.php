<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->ulid();
            $table->longText('items')->default('[]');
            $table->float('sub_total')->default(0);
            $table->float('discount')->default(0);
            $table->float('delivery_fee')->default(0);
            $table->float('tax_rate');
            $table->enum('tax_included', ['Yes', 'No']);
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone');
            $table->enum('payment_method', ['online', 'cash_on_delivery']);
            $table->enum('paid', ['Yes', 'No'])->default('No');
            $table->dateTime('delivered_at')->nullable();
            $table->string('delivery_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
