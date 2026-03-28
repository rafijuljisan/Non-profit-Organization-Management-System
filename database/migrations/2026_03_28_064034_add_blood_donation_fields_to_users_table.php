<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // যদি কলাম না থাকে, শুধুমাত্র তখনই তৈরি করবে
            if (!Schema::hasColumn('users', 'is_blood_donor')) {
                $table->boolean('is_blood_donor')->default(false)->after('status');
            }
            if (!Schema::hasColumn('users', 'blood_group')) {
                $table->string('blood_group')->nullable()->after('is_blood_donor');
            }
            if (!Schema::hasColumn('users', 'last_donation_date')) {
                $table->date('last_donation_date')->nullable()->after('blood_group');
            }
            if (!Schema::hasColumn('users', 'thana')) {
                $table->string('thana')->nullable()->after('district_id');
            }
            if (!Schema::hasColumn('users', 'area')) {
                $table->string('area')->nullable()->after('thana');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('users', 'is_blood_donor')) $columnsToDrop[] = 'is_blood_donor';
            if (Schema::hasColumn('users', 'last_donation_date')) $columnsToDrop[] = 'last_donation_date';
            if (Schema::hasColumn('users', 'thana')) $columnsToDrop[] = 'thana';
            if (Schema::hasColumn('users', 'area')) $columnsToDrop[] = 'area';
            
            // blood_group যেহেতু আগে থেকেই ছিল, তাই ডাউন করার সময় এটি ডিলিট করা হবে না
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};