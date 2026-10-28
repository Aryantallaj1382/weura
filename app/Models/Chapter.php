<?php

// app/Models/Chapter.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Chapter extends Model
{
    protected $fillable = [
        'manhwa_id', 'chapter_number', 'title', 'description', 'image', 'price'
    ];
    protected  $appends = ['images'];

    public function manhua()
    {
        return $this->belongsTo(Manhwa::class , 'manhwa_id');
    }

    public function getImagesAttribute()
    {
        if (empty($this->image)) {
            return [];
        }

        $path = public_path($this->image);

        if (!File::exists($path) || !File::isDirectory($path)) {
            return [];
        }

        return collect(File::files($path))
            ->filter(function ($file) {
                return in_array(strtolower($file->getExtension()), [
                    'jpg', 'jpeg', 'png', 'webp', 'gif'
                ]);
            })
            ->map(function ($file) {
                return asset('public/'.$this->image . '/' . $file->getFilename());
            })
            ->values()
            ->toArray();
    }

    public function readers()
    {
        return $this->belongsToMany(User::class, 'user_chapters')->withTimestamps();
    }
    public function advertisements()
    {
        return $this->morphMany(Advertisement::class, 'advertisable');
    }

}
