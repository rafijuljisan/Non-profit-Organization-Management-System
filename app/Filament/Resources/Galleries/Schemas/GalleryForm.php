<?php

namespace App\Filament\Resources\Galleries\Schemas;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Media Details')
                    ->schema([
                        Select::make('type')
                            ->label('Media Type')
                            ->options([
                                'photo' => '🖼️ Photo',
                                'youtube' => '▶️ YouTube Video',
                                'facebook' => '📘 Facebook Video',
                            ])
                            ->default('photo')
                            ->required()
                            ->live(),

                        TextInput::make('title')
                            ->label('Title / Caption')
                            ->maxLength(255),

                        TextInput::make('category')
                            ->label('Category')
                            ->placeholder('e.g. ত্রাণ কার্যক্রম, শিক্ষা, স্বাস্থ্য')
                            ->datalist(
                                \App\Models\Gallery::distinct()
                                    ->pluck('category')
                                    ->filter()
                                    ->values()
                                    ->toArray()
                            ),

                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->hint('Lower = shown first'),

                        Textarea::make('description')
                            ->label('Description')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])->columns(2),

                // ── Photo Upload ──────────────────────────────
                Section::make('Photo Upload')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('photo')
                            ->label('Upload Photo')
                            ->collection('photo')
                            ->image()
                            ->imageEditor()
                            ->maxSize(5120)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                            ->columnSpanFull()
                            ->helperText('Supported: JPG, PNG, GIF, WEBP — Max 5MB. Auto-converts to WebP.')
                            ->afterStateUpdated(function ($record, $state) {
                                // After upload, store the media path in photo_path column
                                if ($record && $record->type === 'photo') {
                                    $media = $record->getFirstMedia('photo');
                                    if ($media) {
                                        $record->photo_path = $media->id . '/' . $media->file_name;
                                        $record->saveQuietly();
                                    }
                                }
                            }),
                    ])
                    ->visible(fn($get) => $get('type') === 'photo'),

                // Add this Section after the existing Photo Upload section

                Section::make('Bulk Photo Upload')
                    ->schema([
                        TextInput::make('bulk_category')
                            ->label('Category for all these photos')
                            ->placeholder('e.g. ঈদ সামগ্রী বিতরণ-২০২৬')
                            // 🚨 Fix: Removed the \Filament\Forms\Get type-hint
                            ->required(fn ($get) => filled($get('bulk_photo_files')))
                            ->dehydrated(false) 
                            ->columnSpanFull(),

                        \Filament\Forms\Components\FileUpload::make('bulk_photo_files')
                            ->label('Upload Multiple Photos')
                            ->multiple()
                            ->image()
                            ->disk('public')
                            ->directory('gallery/bulk')
                            ->maxFiles(50)
                            ->storeFiles(false)  
                            ->dehydrated(false) 
                            ->columnSpanFull()
                            ->helperText('Select multiple photos — each will become a separate gallery entry.'),
                    ])
                    // 🚨 Fix: Removed the \Filament\Forms\Get type-hint
                    ->visible(fn (string $operation, $get) => $operation === 'create' && $get('type') === 'photo'),
                // ── Video URL ────────────────────────────────
                Section::make('Video URL')
                    ->schema([
                        TextInput::make('video_url')
                            ->label('Video Link')
                            ->url()
                            ->placeholder('https://www.youtube.com/watch?v=... or https://www.facebook.com/...')
                            ->columnSpanFull()
                            ->helperText('Paste the full YouTube or Facebook video URL.'),
                    ])
                    ->visible(fn($get) => in_array($get('type'), ['youtube', 'facebook'])),

                // ── Visibility ───────────────────────────────
                Section::make('Visibility')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Show on Public Site')
                            ->default(true)
                            ->inline(false),
                    ]),
            ]);
    }
}