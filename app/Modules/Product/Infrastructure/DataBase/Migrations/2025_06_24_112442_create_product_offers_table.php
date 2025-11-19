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
        Schema::create('product_offers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade');
            $table->float('offer_price')->nullable();
            $table->dateTime('from_date')->nullable();
            $table->dateTime('to_date')->nullable();            $table->foreign('created_by')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('employees')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_offers');
    }
};
