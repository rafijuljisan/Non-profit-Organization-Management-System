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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // যেমন: office_rent, event_cost, logistics
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->decimal('amount', 10, 2);
            $table->foreignId('created_by')->constrained('users'); // কে এন্ট্রি করেছে
            $table->foreignId('approved_by')->nullable()->constrained('users'); // কে অ্যাপ্রুভ করেছে
            $table->string('voucher_upload')->nullable(); // ভাউচারের ছবি/পিডিএফ
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
