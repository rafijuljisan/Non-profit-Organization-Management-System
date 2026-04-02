<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\Users\UserResource\RelationManagers\DonationHistoriesRelationManager;


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;
    protected static string|UnitEnum|null $navigationGroup = 'Member Management';
    // 🔍 ১. গ্লোবাল সার্চের জন্য মেইন ফিল্ড
    protected static ?string $recordTitleAttribute = 'name';

    // 🔍 ২. কোন কোন ফিল্ড দিয়ে সার্চ করা যাবে তা বলে দেওয়া (নাম, ইমেইল, ফোন, NID)
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email', 'phone', 'nid_number'];
    }

    // 🔍 ৩. সার্চ রেজাল্টে নামের নিচে ফোন নম্বর বা ইমেইল দেখানো (যাতে সহজে চেনা যায়)
    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Phone' => $record->phone ?? 'N/A',
            'District' => $record->district->name ?? 'N/A',
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            DonationHistoriesRelationManager::class,
        ];
    }
    // 🛡️ Data Security: Filter users based on Admin Role
    // 🛡️ Data Security & Global Blood Donors Access
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        // District Admin নিজের জেলার ইউজারদের দেখতে পারবে, 
        // তবে "ব্লাড ডোনার" হলে সারা বাংলাদেশের সবাইকে দেখতে পারবে।
        if ($user !== null && $user->hasRole('District Admin')) {
            $query->where(function ($q) use ($user) {
                $q->where('district_id', $user->district_id)
                    ->orWhere('is_blood_donor', true); // 🚀 যেকোনো জেলার ব্লাড ডোনার শো করবে
            });
        }
        return $query;
    }
}