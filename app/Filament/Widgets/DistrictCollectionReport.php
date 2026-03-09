<?php

namespace App\Filament\Widgets;

use App\Models\Donation;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class DistrictCollectionReport extends BaseWidget
{
    // 🚀 ড্যাশবোর্ডে চার্টগুলোর নিচে পুরো জায়গা জুড়ে দেখানোর জন্য
    protected int | string | array $columnSpan = 'full';
    
    // উইজেটের সিরিয়াল
    protected static ?int $sort = 3; 

    protected static ?string $heading = 'District-wise Collection Report (Completed Donations)';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Donation::query()
                    // ১. Donations এর সাথে Users এবং Districts টেবিল জয়েন করা হলো
                    ->leftJoin('users', 'donations.user_id', '=', 'users.id')
                    ->leftJoin('districts', 'users.district_id', '=', 'districts.id')
                    ->select(
                        // ফিলামেন্টের টেবিলের জন্য একটি ভার্চুয়াল ID তৈরি
                        DB::raw('MIN(donations.id) as id'), 
                        // যদি ইউজার লগইন না করে ডোনেট করে, তবে তার জেলা "General/Anonymous" দেখাবে
                        DB::raw('COALESCE(districts.name, "General / Anonymous") as district_name'), 
                        DB::raw('SUM(donations.amount) as total_collection'), 
                        DB::raw('COUNT(donations.id) as total_donors')
                    )
                    ->where('donations.status', 'completed')
                    ->groupBy('district_name')
            )
            ->columns([
                Tables\Columns\TextColumn::make('district_name')
                    ->label('District (জেলা)')
                    ->sortable()
                    ->searchable()
                    ->weight('bold')
                    ->color('primary'),
                    
                Tables\Columns\TextColumn::make('total_donors')
                    ->label('Total Donations (সংখ্যা)')
                    ->badge()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('total_collection')
                    ->label('Total Collection (৳)')
                    ->money('bdt')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),
            ])
            ->defaultSort('total_collection', 'desc'); 
    }
}