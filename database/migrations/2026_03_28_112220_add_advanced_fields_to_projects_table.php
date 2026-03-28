<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            // 🚀 প্রথমে details কলাম যুক্ত করা হলো (description এর পর)
            $table->longText('details')->nullable()->after('description');
            
            // 🚀 এরপর বাকি সব অ্যাডভান্সড কলাম যুক্ত করা হলো
            $table->json('objectives')->nullable()->after('details'); // লক্ষ্য-উদ্দেশ্য (Array)
            $table->string('beneficiaries')->nullable()->after('objectives'); // উপকারভোগী
            $table->json('expense_sectors')->nullable()->after('beneficiaries'); // ব্যয়ের খাত (Array)
            $table->string('project_area')->nullable()->after('expense_sectors'); // প্রকল্পের এলাকা
            $table->string('duration')->nullable()->after('project_area'); // মেয়াদ
            $table->json('gallery')->nullable()->after('duration'); // গ্যালারি (Multiple Images)
            $table->json('faqs')->nullable()->after('gallery'); // FAQ বা সাধারণ জিজ্ঞাসা (Question & Answer)
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'details', 
                'objectives', 
                'beneficiaries', 
                'expense_sectors', 
                'project_area', 
                'duration', 
                'gallery', 
                'faqs'
            ]);
        });
    }
};