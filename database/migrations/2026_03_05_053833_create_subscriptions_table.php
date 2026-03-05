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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('month'); // কোন মাসের চাঁদা (যেমন: 2026-03-01)
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['paid', 'unpaid', 'partial'])->default('unpaid');
            $table->string('transaction_id')->nullable();
            $table->string('payment_method')->nullable(); // bkash, nagad, bank, cash
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
