<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Page Content')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, Set $set) {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        FileUpload::make('featured_image')
                            ->image()
                            ->directory('pages')
                            ->columnSpanFull(),

                        RichEditor::make('content')
                            ->required()
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->default(true),
                    ])->columns(2),
            ]);
    }
}