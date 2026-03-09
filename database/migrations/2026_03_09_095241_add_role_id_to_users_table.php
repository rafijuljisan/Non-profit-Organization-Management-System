<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Adding the column with an index for better query performance
            $table->unsignedBigInteger('role_id')->nullable()->index()->after('id');
            
            // Note: If you have a 'roles' table, it's best practice to add a foreign key constraint.
            // If so, uncomment the line below:
            // $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // $table->dropForeign(['role_id']); // Uncomment if you used the foreign key above
            $table->dropIndex(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};