<?php

namespace App\Http\Controllers\Api\Auther;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Manhwa;
use Illuminate\Http\Request;

class ManhwaController extends Controller
{

    public function index(Request $request)
    {
        $user = auth()->user();
        $manhwa = Manhwa::where('author_id', $user->id)->latest();
        if ($request->filled('search')) {
            $manhwa->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $manhwa->where('status', $request->status );
        }
        $manhwa = $manhwa->paginate();
        $manhwa->getCollection()->transform(function ($manhwa) {
            return [
                'id' => $manhwa->id,
                'cover_image' => $manhwa->cover_image,
                'title' => $manhwa->title,
                'status' => $manhwa->status,
                'chapter_count' => $manhwa->chapters->count(),
                'updated_at' => $manhwa->updated_at,
            ];
        });
        return api_response($manhwa);

    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'summary' => 'required',
            'cover_image' => 'required',
            'artist_id' => 'nullable',
        ]);
        $imagePath = $request->file('cover_image')->store('manhwa', 'public');
        $user = auth()->user();
        $manhwa  = Manhwa::create([
            'title' => $request->title,
            'summary' => $request->summary,
            'cover_image' => $imagePath,
            'author_id' =>$user->id,
            'artist_id' => $request->artist_id,
        ]);
        return api_response($manhwa);

    }
    public function update(Request $request, Manhwa $manhwa)
    {
        $request->validate([
            'title' => 'required',
            'summary' => 'required',
            'cover_image' => 'required',
            'artist_id' => 'nullable',

        ]);
        if ($request->hasFile('cover_image')) {
            if ($manhwa->cover_image && \Storage::disk('public')->exists($manhwa->cover_image)) {
                \Storage::disk('public')->delete($manhwa->cover_image);
            }
            $cover_imagePath = $request->file('cover_image')->store('manhwa', 'public');
            $manhwa->cover_image = $cover_imagePath;
        }
        $manhwa->update([
            'title' => $request->title,
            'summary' => $request->summary,
            'cover_image' => $manhwa->cover_image,
            'author_id' => $request->author_id,
            'artist_id' => $request->artist_id,

        ]);

        return api_response($manhwa);
    }
    public function destroy(Manhwa $manhwa)
    {
        if ($manhwa->cover_image && \Storage::disk('public')->exists($manhwa->cover_image)) {
            \Storage::disk('public')->delete($manhwa->cover_image);
        }
        $manhwa->delete();
        return api_response($manhwa);
    }



}
