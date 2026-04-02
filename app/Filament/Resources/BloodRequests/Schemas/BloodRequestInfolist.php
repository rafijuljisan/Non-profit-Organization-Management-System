<?php

namespace App\Filament\Resources\BloodRequests\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;

class BloodRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        
                        // ===== Column 1: Assigned Donor Information =====
                        Section::make('🩸 Assigned Donor Information')
                            ->columnSpan(1)
                            ->schema([
                                TextEntry::make('donor.name')
                                    ->label('Donor Name')
                                    ->weight('bold')
                                    ->color('primary'),

                                TextEntry::make('donor.phone')
                                    ->label('Donor Phone')
                                    ->copyable()
                                    ->icon('heroicon-m-phone'),
                                    
                                TextEntry::make('donor.blood_group')
                                    ->label('Blood Group')
                                    ->badge()
                                    ->color('danger'),

                                // ===== নতুন Last Donation Date =====
                                TextEntry::make('donor.last_donation_date')
    ->label('Last Donation Date')
    ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('M d, Y') : 'Never Donated')
    ->badge(fn ($state) => !$state) // যদি 'Never Donated' হয়, তবে ব্যাজ আকারে দেখাবে
    ->color(fn ($state) => $state ? 'gray' : 'warning'),

                                TextEntry::make('donor.district.name')
                                    ->label('District')
                                    ->default('N/A'),

                                TextEntry::make('donor.thana')
                                    ->label('Upazila / Thana')
                                    ->default('N/A'),
                            ]),

                        // ===== Column 2: Requester Information =====
                        Section::make('👤 Requester Information')
                            ->columnSpan(1)
                            ->schema([
                                TextEntry::make('status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'pending' => 'warning',
                                        'accepted' => 'success',
                                        'rejected' => 'danger',
                                        default => 'gray',
                                    }),

                                TextEntry::make('requester_name')->label('Requester Name'),
                                TextEntry::make('requester_phone')->label('Requester Contact')->copyable()->icon('heroicon-m-phone'),
                                TextEntry::make('patient_name')->label('Patient Name'),
                                TextEntry::make('hospital_name')->label('Hospital Location'),
                                TextEntry::make('bags_needed')->label('Bags Required')->numeric(),
                                TextEntry::make('created_at')->label('Requested On')->dateTime('M d, Y h:i A'),
                            ]),
                    ]),

                // ===== Note Section (Full Width) =====
                Section::make('📝 Admin Note / Remarks')
                    ->schema([
                        TextEntry::make('note')
                            ->hiddenLabel() // সেকশন টাইটেল থাকায় ফিল্ডের লেবেল হাইড করা হলো
                            ->default('কোনো নোট যোগ করা হয়নি।')
                            ->columnSpanFull()
                            ->markdown(), // কেউ চাইলে বোল্ড/ইটালিক করতে পারবে
                    ]),
            ]);
    }
}