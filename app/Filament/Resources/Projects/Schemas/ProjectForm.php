<?php

namespace App\Filament\Resources\Projects\Schemas;

// 🚀 FIXED: Tabs এবং Tab এখন Schemas namespace থেকে ইমপোর্ট করা হলো
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab; 

// ইনপুট ফিল্ডগুলো আগের মতোই Forms namespace এ থাকবে
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Project Details')
                    ->tabs([
                        
                        // 🟢 ট্যাব ১: বেসিক তথ্য
                        Tab::make('Basic Info')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Project Name'),
                                Select::make('district_id')
                                    ->relationship('district', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->label('District (Optional)'),
                                Textarea::make('description')
                                    ->label('Short Description (Card View)')
                                    ->rows(3)
                                    ->columnSpanFull(),
                                RichEditor::make('details')
                                    ->label('Full Project Details')
                                    ->columnSpanFull(),
                            ])->columns(2),

                        // 🟢 ট্যাব ২: সামারি ও ফিচারস
                        Tab::make('Project Summary')
                            ->icon('heroicon-o-list-bullet')
                            ->schema([
                                TagsInput::make('objectives')
                                    ->label('প্রকল্পের লক্ষ্য-উদ্দেশ্য (Enter দিয়ে আলাদা করুন)')
                                    ->placeholder('যেমন: দুর্গতদের উদ্ধার কার্যক্রম, ত্রাণ বিতরণ'),
                                TagsInput::make('expense_sectors')
                                    ->label('ব্যয়ের খাত (Enter দিয়ে আলাদা করুন)')
                                    ->placeholder('যেমন: উপকরণ ক্রয়, সার্বিক ব্যবস্থাপনা'),
                                TextInput::make('beneficiaries')
                                    ->label('উপকারভোগী')
                                    ->placeholder('যেমন: ধর্ম-বর্ণ নির্বিশেষে দুর্গত মানুষ'),
                                TextInput::make('project_area')
                                    ->label('প্রকল্পের এলাকা')
                                    ->placeholder('যেমন: ৬৪ জেলা'),
                                TextInput::make('duration')
                                    ->label('মেয়াদ')
                                    ->placeholder('যেমন: দুর্যোগের শুরু থেকে পুনর্বাসন পর্যন্ত'),
                            ])->columns(2),

                        // 🟢 ট্যাব ৩: গ্যালারি এবং FAQ
                        Tab::make('Gallery & FAQs')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                FileUpload::make('gallery')
                                    ->label('প্রজেক্ট গ্যালারি (একাধিক ছবি দিন)')
                                    ->disk('public') // 🚀 FIXED: এটি পাবলিক ফোল্ডারে (storage/app/public) সেভ করবে
                                    ->multiple()
                                    ->image()
                                    ->directory('projects/gallery')
                                    ->reorderable()
                                    ->columnSpanFull(),
                                    
                                Repeater::make('faqs')
                                    ->label('FAQ বা সাধারণ জিজ্ঞাসা')
                                    ->schema([
                                        TextInput::make('question')->label('প্রশ্ন')->required(),
                                        Textarea::make('answer')->label('উত্তর')->required()->rows(2),
                                    ])
                                    ->collapsible()
                                    ->columnSpanFull()
                                    ->addActionLabel('নতুন জিজ্ঞাসা যুক্ত করুন'),
                            ]),
                            
                        // 🟢 ট্যাব ৪: বাজেট
                        Tab::make('Budget & Status')
                            ->icon('heroicon-o-banknotes')
                            ->schema([
                                DatePicker::make('start_date'),
                                DatePicker::make('end_date'),
                                TextInput::make('target_budget')
                                    ->numeric()
                                    ->default(0)
                                    ->prefix('৳'),
                                Select::make('status')
                                    ->options([
                                        'planning' => 'Planning', 
                                        'ongoing' => 'Ongoing', 
                                        'completed' => 'Completed'
                                    ])
                                    ->default('planning')
                                    ->required(),
                            ])->columns(2),
                            
                    ])->columnSpanFull()
            ]);
    }
}