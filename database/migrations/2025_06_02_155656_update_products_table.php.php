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
        Schema::table('products', function (Blueprint $table) {
            $table->string('sku')->unique()->nullable(); // унікальний артикул
            $table->string('supplier_code')->nullable();
            $table->string('country')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('brand')->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('multiplicity')->nullable();
        });
    }
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'sku',
                'supplier_code',
                'country',
                'manufacturer',
                'brand',
                'purchase_price',
                'sale_price',
                'quantity',
                'multiplicity',
            ]);
        });
    }
};
