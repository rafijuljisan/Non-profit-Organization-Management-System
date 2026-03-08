@extends('layouts.app')

@section('title', 'গ্যালারি - ' . ($settings->site_name ?? config('app.name')))

@section('content')

{{-- Hero --}}
<section class="relative bg-gradient-to-br from-blue-950 via-blue-900 to-blue-700 text-white py-16 px-4 text-center overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/asfalt-dark.png')]"></div>
    <div class="relative max-w-2xl mx-auto">
        <p class="text-blue-300 uppercase tracking-widest text-sm font-semibold mb-3">আমাদের কার্যক্রম</p>
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4">ফটো ও ভিডিও গ্যালারি</h1>
        <p class="text-blue-100 text-lg">আমাদের কার্যক্রমের মুহূর্তগুলো ধরে রাখা হয়েছে এখানে।</p>
    </div>
</section>

{{-- Pass all media data to JS safely --}}
<script>
const galleryData = {
    @foreach($allMedia as $item)
    "{{ $item->id }}": {
        type: "{{ $item->type }}",
        @if($item->type === 'photo')
        photoUrl: @json($item->photo_url ?? ''),
        thumbUrl: @json($item->thumb_url ?? $item->photo_url ?? ''),
        @elseif($item->type === 'youtube')
        embedUrl: @json($item->embed_url ?? ''),
        @elseif($item->type === 'facebook')
        videoUrl: @json($item->video_url ?? ''),
        @endif
        title: @json($item->title ?? ''),
        description: @json($item->description ?? ''),
    },
    @endforeach
};
</script>

<div class="max-w-7xl mx-auto px-4 py-14">

    {{-- Filter Tabs --}}
    @if($allMedia->isNotEmpty())
    <div class="flex flex-wrap gap-2 mb-10 justify-center">
        <button onclick="filterGallery('all', this)"
            class="filter-btn px-5 py-2 rounded-full text-sm font-bold border-2 border-blue-600 bg-blue-600 text-white transition">
            সবকিছু ({{ $allMedia->count() }})
        </button>
        @if($photos->count())
        <button onclick="filterGallery('photo', this)"
            class="filter-btn px-5 py-2 rounded-full text-sm font-bold border-2 border-gray-200 text-gray-600 hover:border-blue-600 hover:text-blue-600 transition">
            📷 ছবি ({{ $photos->count() }})
        </button>
        @endif
        @if($videos->count())
        <button onclick="filterGallery('video', this)"
            class="filter-btn px-5 py-2 rounded-full text-sm font-bold border-2 border-gray-200 text-gray-600 hover:border-blue-600 hover:text-blue-600 transition">
            🎬 ভিডিও ({{ $videos->count() }})
        </button>
        @endif
        @foreach($categories as $cat)
        <button onclick="filterGallery('cat-{{ Str::slug($cat) }}', this)"
            class="filter-btn px-5 py-2 rounded-full text-sm font-bold border-2 border-gray-200 text-gray-600 hover:border-blue-600 hover:text-blue-600 transition">
            {{ $cat }}
        </button>
        @endforeach
    </div>
    @endif

    {{-- Empty State --}}
    @if($allMedia->isEmpty())
    <div class="text-center py-20 text-gray-400">
        <p class="text-5xl mb-4">🖼️</p>
        <p class="font-bold text-gray-500 text-lg">এখনো কোনো মিডিয়া যোগ করা হয়নি।</p>
    </div>

    @else

    {{-- Masonry Grid --}}
    <div class="columns-2 sm:columns-3 lg:columns-4 gap-4 space-y-4" id="galleryGrid">
        @foreach($allMedia as $item)
        @php
            $dataType = $item->type === 'photo' ? 'photo' : 'video';
            $dataCat  = $item->category ? 'cat-' . Str::slug($item->category) : '';
        @endphp

        <div class="gallery-item break-inside-avoid mb-4"
            data-type="{{ $dataType }}"
            data-cat="{{ $dataCat }}">

            {{-- ── Photo ── --}}
            @if($item->type === 'photo')
            <div class="relative group cursor-pointer rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition"
                onclick="openLightbox({{ $item->id }})">
                @php $imgSrc = $item->thumb_url ?? $item->photo_url ?? null; @endphp
                @if($imgSrc)
                <img
                    src="{{ $imgSrc }}"
                    alt="{{ $item->title }}"
                    loading="lazy"
                    class="w-full object-cover group-hover:scale-105 transition duration-500"
                    onerror="this.parentElement.innerHTML='<div class=\'w-full h-40 bg-gray-100 flex items-center justify-center text-gray-400 text-xs\'>Image not found</div>'">
                @else
                <div class="w-full h-40 bg-gray-100 flex items-center justify-center">
                    <span class="text-gray-400 text-sm">No image uploaded</span>
                </div>
                @endif
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition flex items-end">
                    @if($item->title)
                    <div class="w-full px-3 py-2 text-white text-xs font-semibold translate-y-full group-hover:translate-y-0 transition duration-300 bg-gradient-to-t from-black/70 to-transparent">
                        {{ $item->title }}
                    </div>
                    @endif
                </div>
                <div class="absolute top-2 right-2 bg-white bg-opacity-80 backdrop-blur rounded-full w-7 h-7 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition">
                    🔍
                </div>
            </div>

            {{-- ── YouTube ── --}}
            @elseif($item->type === 'youtube')
            <div class="relative group cursor-pointer rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition bg-gray-900"
                onclick="openVideoModal({{ $item->id }})">
                @if($item->thumbnail_url)
                <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" loading="lazy"
                    class="w-full object-cover opacity-90 group-hover:opacity-100 transition duration-300">
                @else
                <div class="w-full h-44 bg-gray-800 flex items-center justify-center">
                    <span class="text-gray-500 text-sm">YouTube Video</span>
                </div>
                @endif
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-14 h-14 bg-white bg-opacity-90 rounded-full flex items-center justify-center shadow-lg group-hover:scale-110 transition duration-300">
                        <svg class="w-6 h-6 text-red-600 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </div>
                </div>
                <div class="absolute top-2 left-2">
                    <span class="bg-red-600 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow">YouTube</span>
                </div>
                @if($item->title)
                <div class="absolute bottom-0 left-0 right-0 px-3 py-2 bg-gradient-to-t from-black/80 to-transparent">
                    <p class="text-white text-xs font-semibold truncate">{{ $item->title }}</p>
                </div>
                @endif
            </div>

            {{-- ── Facebook ── --}}
            @elseif($item->type === 'facebook')
            <a href="{{ $item->video_url }}" target="_blank" rel="noopener noreferrer"
                class="relative group block rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition bg-gray-900">
                <div class="w-full h-44 bg-gradient-to-br from-blue-800 to-blue-600 flex flex-col items-center justify-center">
                    <svg class="w-10 h-10 text-white opacity-60 mb-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    <span class="text-white text-xs font-bold opacity-80">Facebook Video</span>
                </div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-14 h-14 bg-white bg-opacity-90 rounded-full flex items-center justify-center shadow-lg group-hover:scale-110 transition duration-300">
                        <svg class="w-6 h-6 text-blue-600 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </div>
                </div>
                <div class="absolute top-2 left-2">
                    <span class="bg-blue-600 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow">Facebook</span>
                </div>
                <div class="absolute top-2 right-2">
                    <span class="bg-black bg-opacity-50 text-white text-xs font-bold px-2 py-0.5 rounded-full">↗ খুলুন</span>
                </div>
                @if($item->title)
                <div class="absolute bottom-0 left-0 right-0 px-3 py-2 bg-gradient-to-t from-black/80 to-transparent">
                    <p class="text-white text-xs font-semibold truncate">{{ $item->title }}</p>
                </div>
                @endif
            </a>
            @endif

        </div>
        @endforeach
    </div>
    @endif
</div>

{{-- ══ PHOTO LIGHTBOX ══ --}}
<div id="lightbox"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/90 p-4 backdrop-blur-sm"
    onclick="closeLightbox()">
    <button class="absolute top-5 right-5 text-white text-4xl leading-none hover:text-gray-300 z-10"
        onclick="closeLightbox()">✕</button>
    <div class="relative max-w-5xl w-full max-h-full flex flex-col items-center" onclick="event.stopPropagation()">
        <img id="lightboxImg" src="" alt=""
            class="max-w-full max-h-[80vh] rounded-2xl object-contain shadow-2xl">
        <div class="mt-4 text-center">
            <p id="lightboxTitle" class="text-white font-extrabold text-lg"></p>
            <p id="lightboxDesc" class="text-gray-300 text-sm mt-1"></p>
        </div>
    </div>
</div>

{{-- ══ VIDEO MODAL ══ --}}
<div id="videoModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/90 p-4 backdrop-blur-sm"
    onclick="closeVideoModal()">
    <button class="absolute top-5 right-5 text-white text-4xl leading-none hover:text-gray-300 z-10"
        onclick="closeVideoModal()">✕</button>
    <div class="relative w-full max-w-4xl" onclick="event.stopPropagation()">
        <div id="videoContainer" class="relative pb-[56.25%] h-0 rounded-2xl overflow-hidden shadow-2xl bg-black">
            {{-- iframe injected by JS --}}
        </div>
        <p id="videoTitle" class="text-white font-extrabold text-lg mt-4 text-center"></p>
    </div>
</div>

<script>
    // ── Filter ──────────────────────────────────────────────
    function filterGallery(filter, btn) {
        document.querySelectorAll('.filter-btn').forEach(b => {
            b.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
            b.classList.add('border-gray-200', 'text-gray-600');
        });
        btn.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
        btn.classList.remove('border-gray-200', 'text-gray-600');

        document.querySelectorAll('.gallery-item').forEach(item => {
            if (filter === 'all') {
                item.style.display = '';
            } else if (filter === 'photo' || filter === 'video') {
                item.style.display = item.dataset.type === filter ? '' : 'none';
            } else {
                item.style.display = item.dataset.cat === filter ? '' : 'none';
            }
        });
    }

    // ── Lightbox ─────────────────────────────────────────
    function openLightbox(id) {
        const data = galleryData[id];
        if (!data || !data.photoUrl) return;

        document.getElementById('lightboxImg').src = data.photoUrl;
        document.getElementById('lightboxTitle').textContent = data.title;
        document.getElementById('lightboxDesc').textContent = data.description;
        const el = document.getElementById('lightbox');
        el.classList.remove('hidden');
        el.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        document.getElementById('lightbox').classList.add('hidden');
        document.getElementById('lightbox').classList.remove('flex');
        document.body.style.overflow = '';
    }

    // ── Video Modal ───────────────────────────────────────
    function openVideoModal(id) {
        const data = galleryData[id];
        if (!data || !data.embedUrl) return;

        // Inject iframe fresh each time (prevents autoplay issues)
        const container = document.getElementById('videoContainer');
        container.innerHTML = `<iframe
            src="${data.embedUrl}"
            class="absolute top-0 left-0 w-full h-full"
            frameborder="0"
            allow="autoplay; fullscreen; encrypted-media; picture-in-picture"
            allowfullscreen></iframe>`;

        document.getElementById('videoTitle').textContent = data.title;
        const el = document.getElementById('videoModal');
        el.classList.remove('hidden');
        el.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeVideoModal() {
        // Remove iframe completely to stop video/audio
        document.getElementById('videoContainer').innerHTML = '';
        document.getElementById('videoModal').classList.add('hidden');
        document.getElementById('videoModal').classList.remove('flex');
        document.body.style.overflow = '';
    }

    // Escape key
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') { closeLightbox(); closeVideoModal(); }
    });
</script>

@endsection