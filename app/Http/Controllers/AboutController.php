<?php

namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function show()
    {
        $about = About::first();
        return view('about', compact('about'));
    }

    public function edit()
    {
        $about = About::first();
        return view('admin.about.edit', compact('about'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'required|string',
            'profile_image' => 'nullable|url',
            'contact_info' => 'nullable|string',
        ]);

        $about = About::first();
        if ($about) {
            $about->update($validated);
        } else {
            About::create($validated);
        }

        return redirect()->route('admin.about.edit')->with('success', 'About information updated successfully.');
    }
}
