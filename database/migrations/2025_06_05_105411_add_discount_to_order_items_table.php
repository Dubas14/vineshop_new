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
        Schema::table('order_items', function (Blueprint $table) {
            // Додаємо поле discount (числове, з двома знаками після коми, може бути null, за замовчуванням 0)
            $table->decimal('discount', 8, 2)->nullable()->default(0)->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Видаляємо поле discount при відкаті міграції
            $table->dropColumn('discount');
        });
    }
};
