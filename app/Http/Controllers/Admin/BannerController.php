<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Banner;
use App\Models\Manhwa;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::with('manhuas')->get();
        $manhwas = Manhwa::all(); // برای انتخاب در modal
        return view('admin.banners.index', compact('banners', 'manhwas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'manhwa_id' => 'required|exists:manhwas,id',
        ]);

        Banner::create($data);
        return redirect()->route('admin.banners.index')->with('success', 'بنر با موفقیت اضافه شد.');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'بنر حذف شد.');
    }
}
