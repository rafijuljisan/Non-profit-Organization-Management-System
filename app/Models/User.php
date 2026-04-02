<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use App\Models\BloodDonationHistory;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles, LogsActivity;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            // 🚀 NEW: Blood Donation casts
            'is_blood_donor' => 'boolean',
            'last_donation_date' => 'date',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'phone', 'district_id', 'status', 'member_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName) => "User {$eventName}");
    }

    // 🚀 NEW: Admin Access Security Logic
    public function canAccessPanel(Panel $panel): bool
    {
        // শর্ত ১: ইউজারের স্ট্যাটাস 'active' হতে হবে
        // শর্ত ২: ইউজারের ইমেইল থাকতে হবে (কারণ সাধারণ ডোনাররা ইমেইল ছাড়া অ্যাকাউন্ট খোলে)
        // return $this->status === 'active' && !empty($this->email);

        // 💡 (বিকল্প লজিক): আপনি যেহেতু Spatie Roles ব্যবহার করছেন, চাইলে নিচের লাইনটিও ব্যবহার করতে পারেন:
        // return $this->status === 'active' && $this->hasRole(['admin', 'super_admin']);

        return $this->roles()->exists();
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(MemberDocument::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
    /**
     * Get the donation histories for the user.
     */
    public function donationHistories()
    {
        return $this->hasMany(BloodDonationHistory::class); 
    }
}