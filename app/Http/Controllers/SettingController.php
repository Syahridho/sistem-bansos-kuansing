<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Administrator.');
        }

        $settings = Setting::all()->groupBy('group');
        
        return view('settings.index', compact('settings'));
    }

    /**
     * Update the application settings.
     */
    public function update(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Tindakan ini hanya untuk Administrator.');
        }

        $request->validate([
            'app_name'             => 'required|string|max:100',
            'app_description'      => 'nullable|string|max:500',
            'maintenance_mode'     => 'nullable|in:0,1',
            'maintenance_message'  => 'nullable|string|max:500',
            'app_logo'             => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        // Handle logo upload
        if ($request->hasFile('app_logo')) {
            // Delete old logo if exists
            $oldLogo = Setting::get('app_logo');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }

            $path = $request->file('app_logo')->store('logos', 'public');
            Setting::set('app_logo', $path);
        }

        // Save maintenance mode separately before loop (handles both hidden input fallback and standard checkbox input)
        Setting::set('maintenance_mode', ($request->has('maintenance_mode') && $request->input('maintenance_mode') !== '0') ? '1' : '0');

        // Loop and save settings
        $keys = ['app_name', 'app_description', 'maintenance_message'];
        foreach ($keys as $key) {
            $value = $request->input($key);
            Setting::set($key, $value ?? '');
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
