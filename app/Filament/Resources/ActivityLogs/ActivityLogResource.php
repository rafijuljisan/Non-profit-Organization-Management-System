<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages\ListActivityLogs;
use App\Filament\Resources\ActivityLogResource\Tables\ActivityLogsTable;
use App\Models\Activity;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;
    protected static string|UnitEnum|null $navigationGroup = 'System Management';
    protected static ?string $navigationLabel = 'Activity Logs';

    public static function canViewAny(): bool
    {
        /** @var \Illuminate\Contracts\Auth\Guard $auth */
        $auth = auth();

        /** @var \App\Models\User|null $user */
        $user = $auth->user();

        if (!$user) return false;

        return $user->hasRole('super_admin')
            || $user->hasPermissionTo('activity_view');
    }

    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }
    public static function canDelete($record): bool { return false; }

    public static function table(Table $table): Table
    {
        return ActivityLogsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivityLogs::route('/'),
        ];
    }
}