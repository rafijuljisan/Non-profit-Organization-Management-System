<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Enums\Fit;

class Gallery extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ─── Spatie Media Collections ──────────────────────────

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')
            ->singleFile()
            ->useDisk('public')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('webp')
            ->format('webp')
            ->quality(85)
            ->nonQueued();

        $this->addMediaConversion('thumb')
            ->format('webp')
            ->quality(75)
            ->fit(Fit::Crop, 400, 280)
            ->nonQueued();
    }

    // ─── Accessors ────────────────────────────────────────

    public function getPhotoUrlAttribute(): ?string
{
    $media = $this->getFirstMedia('photo');
    if (!$media) return null;

    $url = $media->getUrl();
    return str_starts_with($url, 'http') ? $url : url($url);
}

public function getThumbUrlAttribute(): ?string
{
    $media = $this->getFirstMedia('photo');
    if (!$media) return null;

    $url = $media->getUrl();
    return str_starts_with($url, 'http') ? $url : url($url);
}

    public function getEmbedUrlAttribute(): ?string
    {
        if (!$this->video_url) return null;

        if ($this->type === 'youtube') {
            $id = $this->extractYoutubeId($this->video_url);
            return $id ? "https://www.youtube.com/embed/{$id}?rel=0&autoplay=1" : null;
        }

        if ($this->type === 'facebook') {
            return $this->video_url;
        }

        return null;
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->type === 'photo') {
            return $this->photo_url;
        }

        if ($this->type === 'youtube' && $this->video_url) {
            $id = $this->extractYoutubeId($this->video_url);
            return $id ? "https://img.youtube.com/vi/{$id}/hqdefault.jpg" : null;
        }

        return null;
    }

    // ─── Helpers ──────────────────────────────────────────

    public function extractYoutubeId(string $url): ?string
    {
        preg_match(
            '/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/',
            $url,
            $matches
        );
        return $matches[1] ?? null;
    }

    // ─── Scopes ───────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}