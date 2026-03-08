<?php

namespace App\Http\Controllers;

use App\Models\Gallery;

class GalleryController extends Controller
{
    public function index()
    {
        $allMedia = Gallery::active()
            ->with('media')
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        $categories = $allMedia
            ->pluck('category')
            ->filter()
            ->unique()
            ->values();

        $photos = $allMedia->where('type', 'photo');
        $videos = $allMedia->whereIn('type', ['youtube', 'facebook']);

        return view('pages.gallery', compact('allMedia', 'categories', 'photos', 'videos'));
    }
}