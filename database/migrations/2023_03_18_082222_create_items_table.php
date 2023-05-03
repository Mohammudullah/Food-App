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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable();
            $table->string('name_display', 255)->nullable();
            $table->float('price')->nullable()->default(0.0);
            $table->bigInteger('restaurant_id')->nullable();
            $table->text('photo')->nullable();
            $table->string('sku', 255)->nullable();
            $table->integer('status')->default(1)->comment('1 = Available, 2 = Sold out, 3 = Hidden')->nullable();
            $table->integer('notes')->default(1)->comment('1 = Enable, 2 = Disable')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
