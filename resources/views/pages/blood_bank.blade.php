@extends('layouts.app')

@section('content')

<style>
    /* ===== Global Font Enforcement (SolaimanLipi Everywhere) ===== */
    body, h1, h2, h3, h4, h5, h6, p, span, a, label, input, select, button, div {
        font-family: 'SolaimanLipi', Arial, sans-serif !important;
    }

    /* ===== Custom CSS for Blood Bank ===== */
    body { background-color: #f8fafc; }
    
    /* 🩸 Hero Section (2-Column Layout) */
    .blood-bank-hero { 
        background-color: #d32f2f; 
        color: white; 
        padding: 40px 20px 85px 20px; /* স্পেস কমানো হয়েছে */
    }
    .hero-inner {
        max-width: 1050px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 30px;
    }
    .hero-left {
        display: flex;
        align-items: center;
        gap: 18px;
        flex: 1.2;
    }
    .drop-icon { 
        background: rgba(255, 255, 255, 0.2); 
        width: 65px; height: 65px; 
        border-radius: 50%; 
        display: flex; align-items: center; justify-content: center; 
        flex-shrink: 0;
    }
    .hero-left-text h1 { font-size: 42px; font-weight: bold; margin: 0 0 6px 0; }
    .hero-left-text p { font-size: 17px; opacity: 0.95; margin: 0; line-height: 1.5; }
    
    .hero-right {
        flex: 0.8;
        background: rgba(0, 0, 0, 0.15);
        padding: 22px 25px;
        border-radius: 12px;
        border-left: 5px solid #fff;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }
    .hero-right p { font-size: 17px; font-weight: bold; margin: 0 0 15px 0; line-height: 1.4; color: #fff;}
    .btn-hero-cta {
        background: white; color: #d32f2f; padding: 12px 22px; border-radius: 8px;
        font-size: 16px; font-weight: bold; text-decoration: none; display: inline-block;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: 0.3s; width: max-content;
    }
    .btn-hero-cta:hover { background: #f8fafc; transform: translateY(-2px); box-shadow: 0 6px 12px rgba(0,0,0,0.15); }

    /* 🔍 Search Box (Bigger Fonts) */
    .search-card {
        background: white; 
        border-radius: 12px; 
        padding: 25px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        max-width: 1050px; 
        margin: -45px auto 35px auto;
        position: relative; 
        z-index: 10;
        border: 1px solid #f1f5f9;
    }
    .search-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; align-items: end; }
    .form-group label { display: block; font-size: 15px; color: #4b5563; font-weight: bold; margin-bottom: 8px;}
    .form-control { width: 100%; padding: 12px 15px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 16px; color: #1f2937; outline: none; transition: 0.3s; background-color: #fff;}
    .form-control:focus { border-color: #d32f2f; box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.1); }
    .btn-search { background: #d32f2f; color: white; border: none; padding: 12px 20px; width: 100%; border-radius: 8px; font-size: 17px; font-weight: bold; cursor: pointer; transition: 0.3s; display: flex; justify-content: center; align-items: center; gap: 8px;}
    .btn-search:hover { background: #b71c1c; }

    /* 📊 Results Header */
    .results-header { max-width: 1050px; margin: 0 auto 15px auto; display: flex; justify-content: space-between; align-items: center; padding: 0 15px;}
    .results-title { font-size: 22px; font-weight: bold; color: #1f2937; }
    .results-meta { display: flex; gap: 10px; align-items: center; flex-wrap: wrap;}
    .badge-count { background: white; border: 1px solid #e5e7eb; padding: 6px 14px; border-radius: 20px; font-size: 14px; font-weight: bold; color: #4b5563; }
    .btn-request-all { background: white; border: 1px solid #fca5a5; padding: 6px 14px; border-radius: 20px; font-size: 14px; font-weight: bold; color: #d32f2f; cursor: pointer; display: flex; align-items: center; gap: 5px; transition: 0.3s;}
    .btn-request-all:hover { background: #fef2f2; }

    /* 📇 Donors Grid */
    .donors-grid { max-width: 1050px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 18px; padding: 0 15px 50px 15px;}
    .donor-card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 18px 18px 15px 18px; text-align: center; position: relative; transition: 0.3s; }
    .donor-card:hover { box-shadow: 0 8px 20px rgba(0,0,0,0.06); transform: translateY(-2px); border-color: #d1d5db;}
    
    .blood-badge { position: absolute; top: 12px; right: 12px; font-size: 15px; font-weight: bold; color: #d32f2f; display: flex; align-items: center; gap: 4px; }
    
    .donor-img { width: 75px; height: 75px; border-radius: 50%; object-fit: cover; border: 2px solid #f8fafc; box-shadow: 0 3px 5px rgba(0,0,0,0.05); margin: 0 auto 10px auto; display: block; }
    .donor-name { font-size: 19px; font-weight: bold; color: #1f2937; margin-bottom: 2px; }
    .donor-location { font-size: 13px; color: #6b7280; display: flex; justify-content: center; align-items: center; gap: 4px; margin-bottom: 15px; }
    
    /* ⏳ Status & Progress Bar */
    .status-wrap { min-height: 35px; margin-bottom: 12px; display: flex; flex-direction: column; justify-content: center;}
    .status-ready { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; padding: 5px 14px; border-radius: 20px; font-size: 13px; font-weight: bold; display: inline-flex; align-items: center; gap: 4px; margin: 0 auto;}
    
    @keyframes fillProgress { from { width: 0%; } }
    .progress-bar-fill { animation: fillProgress 1.5s ease-out forwards; }

    /* 🔘 Card Buttons */
    .card-actions { border-top: 1px solid #f1f5f9; padding-top: 12px; display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
    .btn-action { background: white; border: 1px solid #e5e7eb; padding: 8px 0; border-radius: 6px; font-size: 14px; font-weight: bold; cursor: pointer; display: flex; justify-content: center; align-items: center; gap: 5px; transition: 0.3s; text-decoration: none; }
    .btn-action.call { color: #374151; }
    .btn-action.call:hover { background: #f8fafc; border-color: #d1d5db;}
    .btn-action.request { color: #d32f2f; }
    .btn-action.request:hover { background: #fef2f2; border-color: #fca5a5; }

    /* 📱 Mobile Responsive */
    @media(max-width: 768px) {
        .hero-inner { flex-direction: column; text-align: left; }
        .hero-left { flex-direction: column; align-items: flex-start; margin-bottom: 10px;}
        .hero-right { width: 100%; align-items: stretch; text-align: center; }
        .btn-hero-cta { text-align: center; width: 100%;}
        .blood-bank-hero { padding: 30px 15px 70px 15px; }
        .search-card { margin: -30px 15px 25px 15px; padding: 15px; }
        .donors-grid { grid-template-columns: repeat(auto-fill, minmax(100%, 1fr)); }
    }
</style>

<div class="blood-bank-hero">
    <div class="hero-inner">
        <div class="hero-left">
            <div class="drop-icon">
                <svg xmlns="http://www.w3.org/validator" viewBox="0 0 24 24" style="width: 30px; height: 30px; fill: white;">
                    <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z" />
                </svg>
            </div>
            <div class="hero-left-text">
                <h1>রক্তদাতা খুঁজুন</h1>
                <p>জরুরি মুহূর্তে জীবন বাঁচাতে আপনার এলাকার রক্তদাতাদের খুঁজুন।</p>
            </div>
        </div>
        
        <div class="hero-right">
            <p>আপনিও হতে পারেন একজন জীবন রক্ষাকারী! আজই আমাদের রক্তদাতা তালিকায় যুক্ত হোন।</p>
            @auth
                <a href="{{ route('donor.dashboard') }}" class="btn-hero-cta">প্রোফাইল আপডেট করুন</a>
            @else
                <a href="{{ route('register') }}" class="btn-hero-cta">রক্তদাতা হিসেবে যুক্ত হোন</a>
            @endauth
        </div>
    </div>
</div>

<div class="search-card">
    <form method="GET" action="{{ route('blood_bank.index') }}">
        <div class="search-grid">
            
            <div class="form-group">
                <label>জেলা</label>
                <select name="district_id" class="form-control">
                    <option value="">সকল জেলা</option>
                    @foreach($districts as $district)
                        <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>
                            {{ $district->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>উপজেলা / থানা</label>
                <select name="thana" id="thana_select" class="form-control">
                    <option value="">যেকোনো উপজেলা/থানা</option>
                </select>
            </div>

            <div class="form-group">
                <label>রক্তের গ্রুপ</label>
                <select name="blood_group" class="form-control">
                    <option value="">যেকোনো গ্রুপ</option>
                    @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $group)
                        <option value="{{ $group }}" {{ request('blood_group') == $group ? 'selected' : '' }}>
                            {{ $group }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn-search">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px;">
                        <circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    খুঁজুন
                </button>
            </div>

        </div>
    </form>
</div>

<div class="results-header">
    <div class="results-title">সকল রক্তদাতা</div>
    <div class="results-meta">
        <div class="badge-count">মোট: {{ $donors->total() }} জন</div>
        <button class="btn-request-all">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;">
                <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"></path>
            </svg>
            সবাইকে রিকোয়েস্ট দিন
        </button>
    </div>
</div>

<div class="donors-grid">
    @forelse($donors as $donor)
    <div class="donor-card">
        
        <div class="blood-badge">
            <svg viewBox="0 0 24 24" style="width: 16px; height: 16px; fill: currentColor;">
                <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z" />
            </svg>
            {{ $donor->blood_group }}
        </div>

        @if($donor->photo && file_exists(public_path('storage/' . $donor->photo)))
            <img src="{{ asset('storage/' . $donor->photo) }}" class="donor-img" alt="Photo">
        @else
            <img src="https://ui-avatars.com/api/?name={{ urlencode($donor->name) }}&background=f1f5f9&color=64748b" class="donor-img" alt="Photo">
        @endif

        <h3 class="donor-name">{{ $donor->name }}</h3>
        <div class="donor-location">
            <svg viewBox="0 0 24 24" fill="currentColor" style="width: 14px; height: 14px;">
                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 0 1 0-5 2.5 2.5 0 0 1 0 5z"/>
            </svg>
            {{ $donor->thana ?? 'Unknown' }}, {{ $donor->district->name ?? 'N/A' }}
        </div>

        @php
            $isEligible = false;
            $daysPassed = 0;
            $daysRemaining = 0;
            $progressPercent = 0;

            if(!$donor->last_donation_date) {
                $isEligible = true;
                $progressPercent = 100;
            } else {
                $daysPassed = \Carbon\Carbon::parse($donor->last_donation_date)->startOfDay()->diffInDays(\Carbon\Carbon::now()->startOfDay());
                if($daysPassed >= 90) {
                    $isEligible = true;
                    $progressPercent = 100;
                } else {
                    $daysRemaining = 90 - $daysPassed;
                    $progressPercent = round(($daysPassed / 90) * 100);
                }
            }
        @endphp

        <div class="status-wrap">
            @if($isEligible)
                <span class="status-ready">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20" style="width: 14px; height: 14px;"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                    রক্তদানে প্রস্তুত
                </span>
            @else
                <div style="width: 100%; text-align: left;">
                    <div style="display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 5px;">
                        <span style="color: #6b7280; font-weight: bold;">দিন {{ $daysPassed }}</span>
                        <span style="color: #d32f2f; font-weight: bold;">আর {{ $daysRemaining }} দিন বাকি</span>
                    </div>
                    <div style="width: 100%; background-color: #fee2e2; border-radius: 10px; height: 6px; overflow: hidden;">
                        <div class="progress-bar-fill" style="background-color: #ef4444; height: 6px; border-radius: 10px; width: {{ $progressPercent }}%;"></div>
                    </div>
                </div>
            @endif
        </div>

        <div class="card-actions">
            <a href="tel:{{ $donor->phone }}" class="btn-action call">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                </svg>
                কল
            </a>
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $donor->phone) }}" target="_blank" class="btn-action request">
                <svg viewBox="0 0 24 24" fill="currentColor" style="width: 14px; height: 14px;">
                    <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z" />
                </svg>
                রিকোয়েস্ট
            </a>
        </div>

    </div>
    @empty
    <div style="grid-column: 1 / -1; text-align: center; padding: 40px 0; color: #6b7280;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 45px; height: 45px; margin: 0 auto 10px auto; opacity: 0.5;">
            <circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>
        <h3 style="font-size: 18px; font-weight: bold; color: #374151;">কোনো রক্তদাতা পাওয়া যায়নি</h3>
        <p style="font-size: 15px;">অনুগ্রহ করে অন্য কোনো এলাকা বা রক্তের গ্রুপ সিলেক্ট করে আবার চেষ্টা করুন।</p>
    </div>
    @endforelse
</div>

<div style="max-width: 1050px; margin: 0 auto 50px auto; padding: 0 15px;">
    {{ $donors->withQueryString()->links() }}
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const districtSelect = document.querySelector('select[name="district_id"]');
        const thanaSelect = document.getElementById('thana_select');
        const oldThana = "{{ request('thana') }}"; 

        function loadThanas(districtId) {
            thanaSelect.innerHTML = '<option value="">লোডিং হচ্ছে...</option>';
            
            if(districtId) {
                fetch(`/get-thanas/${districtId}`)
                    .then(response => response.json())
                    .then(data => {
                        thanaSelect.innerHTML = '<option value="">যেকোনো উপজেলা/থানা</option>';
                        for(let key in data) {
                            let selected = (oldThana === data[key]) ? 'selected' : '';
                            thanaSelect.innerHTML += `<option value="${data[key]}" ${selected}>${data[key]}</option>`;
                        }
                    });
            } else {
                thanaSelect.innerHTML = '<option value="">যেকোনো উপজেলা/থানা</option>';
            }
        }

        districtSelect.addEventListener('change', function() {
            loadThanas(this.value);
        });

        if(districtSelect.value) {
            loadThanas(districtSelect.value);
        }
    });
</script>

@endsection