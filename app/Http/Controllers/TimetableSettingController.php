<?php

namespace App\Http\Controllers;

use App\Models\TimetableSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TimetableSettingController extends Controller
{
    /**
     * Ders programı ayarlarını göster
     */
    public function index()
    {
        $setting = TimetableSetting::current();

        return Inertia::render('TimetableSettings/Index', [
            'setting' => $setting,
        ]);
    }

    /**
     * Ders programı ayarlarını güncelle
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'min_block_size' => 'required|integer|min:1|max:6',
            'max_block_size' => 'required|integer|min:1|max:6|gte:min_block_size',
            'enforce_consecutive' => 'required|boolean',
            'split_rules' => 'required|array',
            'split_rules.*' => 'array',
            'split_rules.*.*' => 'integer|min:1',
        ]);

        $setting = TimetableSetting::current();
        $setting->update($data);

        return redirect()->route('timetable-settings.index')
            ->with('success', 'Ders programı ayarları başarıyla güncellendi.');
    }
}
