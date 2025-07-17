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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->boolean('is_active')->default(true);
            $table->string('image')->nullable();

            $table->string('sku')->unique()->nullable();
            $table->string('supplier_code')->nullable();
            $table->string('brand')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('country')->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('multiplicity')->nullable();

            $table->string('region')->nullable();
            $table->string('classification')->nullable();
            $table->string('type')->nullable();
            $table->string('package_type')->nullable();
            $table->string('color')->nullable();
            $table->string('sugar_content')->nullable();
            $table->integer('volume')->nullable();
            $table->string('sort')->nullable();
            $table->text('taste')->nullable();
            $table->text('aroma')->nullable();
            $table->text('pairing')->nullable();
            $table->decimal('old_price', 10, 2)->nullable();

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
