<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('short_des', 500);
            $table->string('price', 50);
            $table->boolean('discount');
            $table->string('discount_price', 50);
            $table->string('image', 200);
            $table->boolean('stock');
            $table->float('star');
            // $table->enum('remark', ['popular', 'new', 'top', 'special', 'trending', 'regular']);
            // Adjusted precision for a broader range
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
