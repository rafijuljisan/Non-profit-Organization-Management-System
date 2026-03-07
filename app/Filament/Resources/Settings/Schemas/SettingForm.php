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