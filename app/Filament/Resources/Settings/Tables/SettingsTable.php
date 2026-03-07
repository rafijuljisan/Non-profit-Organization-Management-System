<?php

namespace App\Filament\Resources\Settings\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction; // <-- সঠিক Filament 4 Action নেমস্পেস

class SettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('site_logo')
                    ->label('Logo')
                    ->disk('public')
                    ->url(fn($record) => asset('storage/' . $record->site_logo)),
                TextColumn::make('site_name')->weight('bold'),
                TextColumn::make('email'),
                TextColumn::make('phone'),
            ])
            ->actions([
                EditAction::make()->label('Update Settings'),
            ])
            ->paginated(false); // পেজিনেশন বন্ধ, কারণ ডাটা একটাই
    }
}