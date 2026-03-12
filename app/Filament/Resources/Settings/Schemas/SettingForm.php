<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('General Information')
                    ->schema([
                        TextInput::make('site_name')->required()->label('Site Name'),
                        TextInput::make('tagline')->label('Tagline / Slogan'),
                        FileUpload::make('site_logo')
                            ->image()
                            ->disk('public')
                            ->directory('settings')
                            ->label('Site Logo'),
                        TextInput::make('email')->email(),
                        TextInput::make('phone'),
                        Textarea::make('address')->rows(2)->columnSpanFull(),
                    ])->columns(2),

                // 🚀 NEW: SEO & Analytics Section
                Section::make('SEO & Analytics')
                    ->description('Manage search engine verification and tracking codes.')
                    ->schema([
                        TextInput::make('google_site_verification')
                            ->label('Google Search Console ID')
                            ->placeholder('e.g. dQw4w9WgXcQ...')
                            ->helperText('Paste only the verification string, not the full HTML meta tag.'),
                            
                        TextInput::make('google_analytics_id')
                            ->label('Google Analytics ID (G-XXXXXXX)')
                            ->placeholder('e.g. G-1234567890'),
                            
                        Textarea::make('meta_description')
                            ->label('Site Meta Description')
                            ->placeholder('Brief description of your organization for search engines.')
                            ->rows(2)
                            ->columnSpanFull(),
                            
                        TextInput::make('meta_keywords')
                            ->label('Site Meta Keywords')
                            ->placeholder('e.g. charity, donation, blood bank, bangladesh')
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Payment Information')
                    ->description('This information will be shown on the donation page.')
                    ->schema([
                        TextInput::make('bkash_number')
                            ->label('bKash Number')
                            ->placeholder('01XXXXXXXXX')
                            ->tel(),
                        TextInput::make('bkash_account_type')
                            ->label('bKash Account Type')
                            ->placeholder('Personal / Merchant'),
                        Textarea::make('bkash_instruction')
                            ->label('bKash Instruction')
                            ->rows(2)
                            ->columnSpanFull()
                            ->placeholder('e.g. Send money to 01XXX... then enter TrxID in the form.'),

                        TextInput::make('nagad_number')
                            ->label('Nagad Number')
                            ->placeholder('01XXXXXXXXX')
                            ->tel(),
                        TextInput::make('nagad_account_type')
                            ->label('Nagad Account Type')
                            ->placeholder('Personal / Merchant'),
                        Textarea::make('nagad_instruction')
                            ->label('Nagad Instruction')
                            ->rows(2)
                            ->columnSpanFull()
                            ->placeholder('e.g. Send money to 01XXX... then enter TrxID in the form.'),

                        TextInput::make('rocket_number')
                            ->label('Rocket Number')
                            ->placeholder('01XXXXXXXXX')
                            ->tel(),
                        Textarea::make('rocket_instruction')
                            ->label('Rocket Instruction')
                            ->rows(2)
                            ->columnSpanFull(),

                        Textarea::make('bank_info')
                            ->label('Bank Account Details')
                            ->rows(3)
                            ->columnSpanFull()
                            ->placeholder("Bank: ABC Bank\nBranch: Dhaka\nAccount Name: ...\nAccount No: ...\nRouting No: ..."),

                        Textarea::make('bank_instruction')
                            ->label('Bank Transfer Instruction')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Social & Footer')
                    ->schema([
                        TextInput::make('facebook_url')->url()->label('Facebook Link'),
                        TextInput::make('youtube_url')->url()->label('YouTube Link'),
                        TextInput::make('google_map_url')
                            ->url()
                            ->label('Google Map Embed URL')
                            ->placeholder('https://www.google.com/maps/embed?...')
                            ->columnSpanFull(),
                        Textarea::make('about_footer')
                            ->rows(3)
                            ->columnSpanFull()
                            ->label('Footer About Text'),
                    ])->columns(2),

            ]);
    }
}