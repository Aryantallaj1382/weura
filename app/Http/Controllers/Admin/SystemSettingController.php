<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manhwa;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
class SystemSettingController extends Controller
{
    public function editSuggested()
    {
        $setting = SystemSetting::where('name', 'suggested')->firstOrFail();
        $manhuas = Manhwa::pluck('title', 'id'); // لیست مانهوآ برای دراپ‌داون
        return view('admin.system_settings.suggested', compact('setting', 'manhuas'));
    }

    public function updateSuggested(Request $request)
    {
        $request->validate([
            'manhua_id' => 'required',
        ]);

        $setting = SystemSetting::where('name', 'suggested')->firstOrFail();
        $setting->value = $request->manhua_id;
        $setting->save();

        return redirect()->back()->with('success', 'تنظیم با موفقیت به‌روزرسانی شد.');
    }
}
