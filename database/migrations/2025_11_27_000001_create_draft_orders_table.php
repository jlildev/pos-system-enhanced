<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('draft_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('draft_number')->unique();
            $table->json('cart_items');
            $table->decimal('total', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_updated_at')->useCurrent();
            $table->timestamps();
            
            $table->index(['user_id', 'is_active']);
            $table->index('last_updated_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('draft_orders');
    }
};