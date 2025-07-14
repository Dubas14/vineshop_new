<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
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
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'region', 'classification', 'type', 'package_type', 'color', 'sugar_content',
                'volume', 'sort', 'taste', 'aroma', 'pairing', 'old_price'
            ]);
        });
    }
};
