<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\HtmlString;

class CampaignProgressWidget extends BaseWidget
{
    public static function canView(): bool
    {
        /** @var \Illuminate\Contracts\Auth\Guard $auth */
        $auth = auth();
        /** @var \App\Models\User|null $user */
        $user = $auth->user();
        
        // Only super_admin or admin can see this widget. 
        // The blood_secretary will be blocked.
        return $user && $user->hasAnyRole(['super_admin', 'admin']);
    }
    // ড্যাশবোর্ডে পুরো জায়গা জুড়ে দেখানোর জন্য
    protected int | string | array $columnSpan = 'full';
    
    // উইজেটের সিরিয়াল (Due Tracker এর নিচে দেখাবে)
    protected static ?int $sort = 4; 

    protected static ?string $heading = 'Ongoing Campaigns Progress (চলমান প্রজেক্ট)';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // শুধুমাত্র ongoing বা active প্রজেক্টগুলো দেখাবে
                Project::query()->whereIn('status', ['ongoing', 'active'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Campaign Name')
                    ->weight('bold')
                    ->searchable()
                    ->description(fn (Project $record): string => 'Starts: ' . \Carbon\Carbon::parse($record->start_date)->format('d M, Y')),

                Tables\Columns\TextColumn::make('target_budget')
                    ->label('Target (৳)')
                    ->money('bdt')
                    ->color('gray'),

                Tables\Columns\TextColumn::make('collected_amount')
                    ->label('Collected (৳)')
                    ->money('bdt')
                    ->color('success')
                    ->weight('bold'),

                // 🚀 Magic Column: HTML ও Tailwind CSS দিয়ে ডায়নামিক প্রগ্রেস বার তৈরি
                Tables\Columns\TextColumn::make('progress')
                    ->label('Fund Progress')
                    ->getStateUsing(function (Project $record) {
                        // শুন্য দিয়ে ভাগ করার এরর (Division by zero) এড়ানোর লজিক
                        $target = $record->target_budget > 0 ? $record->target_budget : 1;
                        $collected = $record->collected_amount ?? 0;
                        
                        // শতকরা (Percentage) হিসাব করা
                        $percentage = min(100, round(($collected / $target) * 100, 1));

                        // কালার লজিক: কম হলে লাল, মাঝামাঝি হলে হলুদ, টার্গেটের কাছাকাছি হলে সবুজ
                        $barColor = match(true) {
                            $percentage >= 80 => 'bg-green-500',
                            $percentage >= 40 => 'bg-blue-500',
                            default => 'bg-orange-500',
                        };

                        return new HtmlString('
                            <div class="flex items-center gap-3">
                                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 min-w-[120px]">
                                    <div class="' . $barColor . ' h-2.5 rounded-full transition-all duration-500" style="width: ' . $percentage . '%"></div>
                                </div>
                                <span class="text-sm font-bold text-gray-700 dark:text-gray-300">' . $percentage . '%</span>
                            </div>
                        ');
                    }),
                    
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Deadline')
                    ->date('d M, Y')
                    ->badge()
                    ->color(function (Project $record): string {
                        $daysLeft = now()->diffInDays($record->end_date, false);
                        if ($daysLeft < 0) return 'danger';
                        if ($daysLeft <= 7) return 'warning';
                        return 'success';
                    })
                    ->description(function (Project $record): string {
                        $daysLeft = now()->diffInDays($record->end_date, false);
                        return $daysLeft > 0 ? (int)$daysLeft . ' days left' : 'Expired';
                    }),
            ])
            ->paginationPageOptions([5]) // একসাথে ৫টি প্রজেক্ট দেখাবে
            ->defaultSort('created_at', 'desc');
    }
}