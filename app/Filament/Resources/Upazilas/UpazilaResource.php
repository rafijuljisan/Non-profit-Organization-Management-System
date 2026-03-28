<?php

namespace App\Filament\Resources\Upazilas;

use App\Filament\Resources\Upazilas\Pages\CreateUpazila;
use App\Filament\Resources\Upazilas\Pages\EditUpazila;
use App\Filament\Resources\Upazilas\Pages\ListUpazilas;
use App\Filament\Resources\Upazilas\Schemas\UpazilaForm;
use App\Filament\Resources\Upazilas\Tables\UpazilasTable;
use App\Models\Upazila;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UpazilaResource extends Resource
{
    protected static ?string $model = Upazila::class;

    // 🚀 আইকন ও লেবেল আপডেট করা হলো
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;
    protected static string|UnitEnum|null $navigationGroup = 'System Management';
    protected static ?string $navigationLabel = 'Upazilas / Thanas';

    protected static ?string $recordTitleAttribute = 'name'; // 🚀 গ্লোবাল সার্চের জন্য নাম ফিল্ড

    public static function form(Schema $schema): Schema
    {
        return UpazilaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UpazilasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUpazilas::route('/'),
            'create' => CreateUpazila::route('/create'),
            'edit' => EditUpazila::route('/{record}/edit'),
        ];
    }
}