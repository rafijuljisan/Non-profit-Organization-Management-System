<?php

namespace App\Filament\Pages;

use App\Models\Donation;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class DonorList extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-users';

    protected static string | \UnitEnum | null $navigationGroup = 'Financial Management';

    protected static ?string $navigationLabel = 'Top Donors';

    protected static ?string $title = 'দাতাদের তালিকা (Donors List)';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.donor-list';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Donation::query()
                    ->where('status', 'completed')
                    // 🚀 FIXED: MAX(id) as id যুক্ত করা হয়েছে যাতে Filament ক্র্যাশ না করে
                    ->selectRaw('MAX(id) as id, donor_phone, MAX(donor_name) as donor_name, SUM(amount) as total_amount, COUNT(id) as total_donations, MAX(created_at) as last_donation_date')
                    ->groupBy('donor_phone')
            )
            ->columns([
                TextColumn::make('donor_name')
                    ->label('দাতার নাম')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->havingRaw('MAX(donor_name) LIKE ?', ["%{$search}%"]);
                    })
                    ->weight('bold'),

                TextColumn::make('donor_phone')
                    ->label('মোবাইল নম্বর')
                    ->searchable()
                    ->copyable()
                    ->tooltip('Click to copy'),

                TextColumn::make('total_donations')
                    ->label('মোট দান (বার)')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('total_amount')
                    ->label('সর্বমোট পরিমাণ')
                    ->money('BDT')
                    ->sortable()
                    ->color('success')
                    ->weight('bold'),

                TextColumn::make('last_donation_date')
                    ->label('সর্বশেষ দান')
                    ->date('d M, Y')
                    ->sortable(),
            ])
            ->defaultSort('total_amount', 'desc');
    }
}