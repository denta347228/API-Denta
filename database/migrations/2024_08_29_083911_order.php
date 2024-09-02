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
        Schema::create('orders', function (Blueprint $table) {
            // BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            // BIGINT UNSIGNED NOT NULL + FOREIGN KEY
            $table->foreignId('product_id')
                ->constrained('products')
                ->onDelete('cascade');
            // BIGINT UNSIGNED NOT NULL + FOREIGN KEY
            $table->unsignedInteger('quantity');
            // INT UNSIGNED NOT NULL
            $table->decimal('total_price', 10, 2);
            // DECIMAL(10, 2) NOT NULL
            $table->timestamp('order_date')->useCurrent();
            // TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            $table->timestamps();
            // created_at and updated_at with DEFAULT CURRENT_TIMESTAMP
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
