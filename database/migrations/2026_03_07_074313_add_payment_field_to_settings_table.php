<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('tagline')->nullable()->after('site_name');
            $table->string('bkash_number')->nullable()->after('phone');
            $table->string('bkash_account_type')->nullable()->after('bkash_number');
            $table->text('bkash_instruction')->nullable()->after('bkash_account_type');
            $table->string('nagad_number')->nullable()->after('bkash_instruction');
            $table->string('nagad_account_type')->nullable()->after('nagad_number');
            $table->text('nagad_instruction')->nullable()->after('nagad_account_type');
            $table->string('rocket_number')->nullable()->after('nagad_instruction');
            $table->text('rocket_instruction')->nullable()->after('rocket_number');
            $table->text('bank_info')->nullable()->after('rocket_instruction');
            $table->text('bank_instruction')->nullable()->after('bank_info');
            $table->text('google_map_url')->nullable()->after('youtube_url');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'tagline',
                'bkash_number', 'bkash_account_type', 'bkash_instruction',
                'nagad_number', 'nagad_account_type', 'nagad_instruction',
                'rocket_number', 'rocket_instruction',
                'bank_info', 'bank_instruction',
                'google_map_url',
            ]);
        });
    }
};