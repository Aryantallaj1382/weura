<?php

namespace App\Http\Controllers\Api\Auther;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Manhwa;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ChapterController extends Controller
{
    public function index(Request $request , $id)
    {
        $user = auth()->user();
        $manhwa = Manhwa::find($id);
        $chapters = $manhwa->chapters;
        return api_response($chapters);

    }
    public function chapters($id)
    {
        $chapter = Chapter::findOrFail($id);

        return api_response([
                'image' => $chapter->images,
                'is_paid' => $chapter->is_paid,
                'chapter_number' => $chapter->chapter_number,
                'title' => $chapter->title,
                'manhwa_id' => $chapter->manhwa_id,
                'price' => $chapter->price,
                'description' => $chapter->description,

            ]

        );
    }
    public function store(Request $request)
    {
        $request->validate(
            [
                'manhwa_id' => 'required|exists:manhwas,id',
                'chapter_number' => 'required|min:1|unique:chapters,chapter_number,NULL,id,manhwa_id,' . $request->manhwa_id,
                'title' => 'nullable',
                'description' => 'nullable',
                'zip' => 'required|file|mimes:zip|max:51200',
            ],
            [
                'manhwa_id.required' => 'انتخاب مانهوا الزامی است.',
                'manhwa_id.exists' => 'مانهوای انتخاب شده معتبر نیست.',

                'chapter_number.required' => 'شماره چپتر الزامی است.',
                'chapter_number.min' => 'شماره چپتر باید حداقل ۱ باشد.',
                'chapter_number.unique' => 'این شماره چپتر قبلاً برای این مانهوا ثبت شده است.',

                'zip.required' => 'فایل ZIP الزامی است.',
                'zip.file' => 'فایل انتخاب شده معتبر نیست.',
                'zip.mimes' => 'فقط فایل با فرمت ZIP مجاز است.',
                'zip.max' => 'حجم فایل نباید بیشتر از ۵۰ مگابایت باشد.',
            ],
            [
                'manhwa_id' => 'مانهوا',
                'chapter_number' => 'شماره چپتر',
                'title' => 'عنوان',
                'description' => 'توضیحات',
                'zip' => 'فایل ZIP',
            ]
        );

        DB::beginTransaction();

        try {

            $manhwa = Manhwa::findOrFail($request->manhwa_id);

            $exists = $manhwa->chapters()
                ->where('chapter_number', $request->chapter_number)
                ->exists();

            if ($exists) {
                return api_response([], 'این شماره چپتر قبلاً برای این مانهوا ثبت شده است.', 422);
            }
            $path = null;
            $destinationPath = null; // تعریف اولیه برای catch

            if ($request->hasFile('zip')) {

                $zipFile = $request->file('zip');

                $folderName = $manhwa->id . '_chapter_' . $request->chapter_number;
                $destinationPath = public_path("uploads/images/{$folderName}");
                if (!is_dir($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $tempPath = storage_path('app/temp');

                if (!File::exists($tempPath)) {
                    File::makeDirectory($tempPath, 0755, true);
                }

                $zipFileName = uniqid('zip_') . '.zip';
                $fullZipPath = $tempPath . '/' . $zipFileName;
                $zipFile->move($tempPath, $zipFileName);
                if (!file_exists($fullZipPath)) {
                    throw new \Exception('فایل zip واقعاً ذخیره نشد');
                }
                $zip = new ZipArchive;
                if ($zip->open($fullZipPath) !== true) {
                    throw new \Exception('باز کردن فایل زیپ ناموفق بود');
                }
                $zip->extractTo($destinationPath);
                $zip->close();
                unlink($fullZipPath);

                $path = "uploads/images/{$folderName}";
            }

            $manhwa->chapters()->create([
                'title' => $request->title,
                'description' => $request->description,
                'image' => $path,
                'chapter_number' => $request->chapter_number,
            ]);

            DB::commit();

            return api_response([], 'با موفقیت ثبت شد');

        } catch (\Throwable $e) {

            DB::rollBack();

            // اگر پوشه ساخته شده بود پاک شود
            if (!empty($destinationPath) && is_dir($destinationPath)) {
                File::deleteDirectory($destinationPath);
            }

            return api_response([
                'error' => $e->getMessage()
            ], 'خطا در ثبت فصل', 500);
        }
    }
    public function update(Request $request, Chapter $chapter)
    {
        $request->validate(
            [
                'manhwa_id' => 'required|exists:manhwas,id',
                'chapter_number' => 'required|unique:chapters,chapter_number,' . $chapter->id . ',id,manhwa_id,' . $chapter->manhwa_id,
                'title' => 'required',
                'description' => 'required',
                'price' => 'required',
                'zip' => 'nullable|file|mimes:zip|max:51200',
            ],
            [
                'manhwa_id.required' => 'انتخاب مانهوا الزامی است.',
                'manhwa_id.exists' => 'مانهوای انتخاب شده معتبر نیست.',

                'chapter_number.required' => 'شماره فصل الزامی است.',
                'chapter_number.unique' => 'این شماره فصل قبلاً برای این مانهوا ثبت شده است.',

                'title.required' => 'عنوان فصل الزامی است.',

                'description.required' => 'توضیحات فصل الزامی است.',

                'price.required' => 'قیمت فصل الزامی است.',

                'zip.file' => 'فایل انتخاب شده معتبر نیست.',
                'zip.mimes' => 'فقط فایل با فرمت ZIP مجاز است.',
                'zip.max' => 'حجم فایل ZIP نباید بیشتر از ۵۰ مگابایت باشد.',
            ]
        );
        DB::beginTransaction();

        $oldPath = $chapter->image;
        $path = null;
        $destinationPath = null;

        try {
            if ($request->hasFile('zip')) {
                $folderName = $chapter->manhwa_id . '_chapter_' . $request->chapter_number;
                $destinationPath = public_path("uploads/images/{$folderName}");

                if (!is_dir($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $tempPath = storage_path('app/temp');
                if (!File::exists($tempPath)) {
                    File::makeDirectory($tempPath, 0755, true);
                }

                $zipFile = $request->file('zip');
                $zipFileName = uniqid('zip_') . '.zip';
                $fullZipPath = $tempPath . '/' . $zipFileName;

                $zipFile->move($tempPath, $zipFileName);
                if (!file_exists($fullZipPath)) {
                    throw new \Exception('فایل zip واقعاً ذخیره نشد');
                }

                $zip = new ZipArchive;
                if ($zip->open($fullZipPath) !== true) {
                    throw new \Exception('باز کردن فایل زیپ ناموفق بود');
                }
                $zip->extractTo($destinationPath);
                $zip->close();
                unlink($fullZipPath);

                // مسیر جدید برای ذخیره در دیتابیس
                $path = "uploads/images/{$folderName}";

                // حذف فولدر قدیمی اگر وجود دارد و با فولدر جدید تفاوت دارد
                if (!empty($oldPath) && $oldPath !== $path) {
                    $oldDir = public_path($oldPath);
                    if (File::exists($oldDir) && is_dir($oldDir)) {
                        File::deleteDirectory($oldDir);
                    }
                }


                // به‌روز رسانی مقدار image برای فصل فعلی
                $chapter->image = $path;
            }

            $chapter->update([
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'chapter_number' => $request->chapter_number,
                'image' => $path ?? $chapter->image,
            ]);

            DB::commit();

            return api_response([], 'با موفقیت ویرایش شد');

        } catch (\Throwable $e) {
            DB::rollBack();

            // اگر فولدر جدیدی ساخته شده بود، در صورت خطا پاک شود
            if (!empty($destinationPath) && is_dir($destinationPath)) {
                File::deleteDirectory($destinationPath);
            }

            return api_response([
                'error' => $e->getMessage()
            ], 'خطا در ویرایش فصل', 500);
        }
    }
    public function delete(Request $request, $id)
    {
        $chapter = Chapter::findOrFail($id);
        if ($chapter->image && \Storage::disk('public')->exists($chapter->image)) {
            \Storage::disk('public')->delete($chapter->image);
        }
        $chapter->delete();
        return api_response([],'با موفقیت حذف شد');

    }

}
