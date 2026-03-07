<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inquiry;

class InquiryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'type' => 'required|in:contact,volunteer',
            'message' => 'required|string|max:1000',
        ]);

        Inquiry::create($request->all());

        return redirect()->back()->with('success', 'Thank you! Your message has been sent successfully. We will contact you soon.');
    }
}