<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manhwa;
use App\Models\OurTeam;
use Illuminate\Http\Request;

class OurTeamController extends Controller
{
    public function index()
    {
        $manhwas = OurTeam::
        latest() // آخرین‌ها اول
        ->paginate(12); // 12 تا در هر صفحه
        return view('admin.ourTeam.index', compact('manhwas'));
    }

    public function create()
    {
        return view('admin.ourTeam.create');

    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'image' => 'required',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/team'), $imageName);
            $data['image'] = 'uploads/team/' . $imageName;
        }

        $manhwa = OurTeam::create($data);



        return redirect()->route('admin.team.create')
            ->with('success', 'کتاب با موفقیت ایجاد شد.');
    }


    public function destroy($id)
    {
        $teamMember = OurTeam::findOrFail($id);

        // حذف فایل تصویر از سرور (اختیاری اما توصیه شده)
        if (file_exists(public_path($teamMember->image))) {
            unlink(public_path($teamMember->image));
        }

        $teamMember->delete();

        return redirect()->route('admin.team.index') // یا هر مسیری که می‌خواهید بعد از حذف برگردید
        ->with('success', 'عضو تیم با موفقیت حذف شد.');
    }

}
