<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser // <-- FilamentUser যুক্ত করা হলো
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * $fillable এর বদলে $guarded ব্যবহার করা হলো, 
     * যাতে আমাদের কাস্টম ফিল্ডগুলো (phone, nid, district_id) সেভ হতে সমস্যা না হয়।
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // Filament প্যানেলে কে লগইন করতে পারবে তার লজিক
    public function canAccessPanel(Panel $panel): bool
    {
        // আপাতত যাদের স্ট্যাটাস active তারা সবাই লগইন করতে পারবে
        // পরবর্তীতে আমরা এখানে Role চেক করে দিতে পারি
        return $this->status === 'active';
    }

    // কোন জেলার সদস্য
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    // সদস্যের সব ডকুমেন্টস (KYC)
    public function documents(): HasMany
    {
        return $this->hasMany(MemberDocument::class);
    }

    // সদস্যের মাসিক চাঁদার হিস্ট্রি
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}