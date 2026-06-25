<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    public function processReportImage(UploadedFile $file, int $reportId): array
    {
        $folder   = "reports/{$reportId}";
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        $originalPath = "{$folder}/original/{$filename}";
        $thumbPath    = "{$folder}/thumb/{$filename}";

        // Simpan file langsung tanpa processing kalau GD tidak tersedia
        Storage::disk('public')->putFileAs("{$folder}/original", $file, $filename);
        Storage::disk('public')->putFileAs("{$folder}/thumb", $file, $filename);

        // Coba proses dengan Intervention Image kalau tersedia
        try {
            if (extension_loaded('gd')) {
                $manager = new \Intervention\Image\ImageManager(
                    new \Intervention\Image\Drivers\Gd\Driver()
                );

                $img = $manager->read($file->getRealPath())
                    ->scaleDown(width: 1200, height: 1200)
                    ->toJpeg(quality: 75);

                Storage::disk('public')->put($originalPath, $img);

                $thumb = $manager->read($file->getRealPath())
                    ->cover(400, 400)
                    ->toJpeg(quality: 70);

                Storage::disk('public')->put($thumbPath, $thumb);
            }
        } catch (\Exception $e) {
            // Fallback: file sudah tersimpan di atas
        }

        $sizeKb = (int) (Storage::disk('public')->size($originalPath) / 1024);

        return [
            'path_original'  => $originalPath,
            'path_thumbnail' => $thumbPath,
            'size_kb'        => $sizeKb,
            'original_name'  => $file->getClientOriginalName(),
        ];
    }

    public function deleteReportImages(int $reportId): void
    {
        Storage::disk('public')->deleteDirectory("reports/{$reportId}");
    }

    public function storeForReport($report, UploadedFile $file, int $order = 0): array
    {
        return $this->processReportImage($file, $report->id);
    }
}