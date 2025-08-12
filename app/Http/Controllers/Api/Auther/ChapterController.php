<?php

namespace App\Http\Controllers\Api\Auther;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Manhwa;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    public function index(Request $request , $id)
    {
        $user = auth()->user();
        $manhwa = Manhwa::find($id);
        $chapters = $manhwa->chapters;
        return api_response($chapters);

    }
    public function store(Request $request)
    {
        $request->validate([
            'manhwa_id' => 'required',
            'chapter_number' => 'required',
            'title' => 'required',
            'description' => 'required',
            'image' => 'required',
        ]);
        $manhwa = Manhwa::find($request->manhwa_id);
        $imagePath = $request->file('image')->store('chapters', 'public');

        $manhwa->chapters()->create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'chapter_number' => $request->chapter_number,
        ]);
        return api_response($manhwa);

    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'chapter_number' => 'required|numeric',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $chapter = Chapter::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($chapter->image && \Storage::disk('public')->exists($chapter->image)) {
                \Storage::disk('public')->delete($chapter->image);
            }
            $imagePath = $request->file('image')->store('chapters', 'public');
            $chapter->image = $imagePath;
        }

        $chapter->title = $request->title;
        $chapter->description = $request->description;
        $chapter->chapter_number = $request->chapter_number;
        $chapter->save();

        return api_response($chapter);
    }
    public function delete(Request $request, $id)
    {
        $chapter = Chapter::findOrFail($id);
        if ($chapter->image && \Storage::disk('public')->exists($chapter->image)) {
            \Storage::disk('public')->delete($chapter->image);
        }
        $chapter->delete();
        return api_response($chapter);

    }

}
