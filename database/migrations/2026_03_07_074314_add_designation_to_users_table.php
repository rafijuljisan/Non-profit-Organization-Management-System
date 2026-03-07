<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add designation to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('designation')->nullable()->after('photo');
            $table->boolean('show_in_volunteer')->default(true)->after('designation');
        });

        // Create designations table for admin-controlled priority
        Schema::create('designations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('priority')->default(99); // lower = higher priority
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['designation', 'show_in_volunteer']);
        });
        Schema::dropIfExists('designations');
    }
};