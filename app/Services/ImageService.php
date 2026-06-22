<?php

namespace App\Services;

use App\Models\Report;
use App\Models\ReportImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Drivers\Gd\Encoders\JpegEncoder;

class ImageService
{
    // Target ukuran setelah kompres (KB) — sesuai requirement "disimpan cuma beberapa KB"
    private const MAX_WIDTH_DISPLAY  = 1280;
    private const MAX_WIDTH_THUMB    = 320;
    private const QUALITY_DISPLAY    = 70;
    private const QUALITY_THUMB      = 60;

    private ImageManager $manager;

    public function __construct()
    {
        // Pakai driver GD bawaan PHP — argumen harus nama class lengkap (string)
        $this->manager = new ImageManager(Driver::class);
    }

    public function storeForReport(Report $report, UploadedFile $file, int $order = 0): ReportImage
    {
        $filename = Str::uuid() . '.jpg'; // selalu simpan sebagai jpg untuk konsistensi kompresi

        $displayPath   = "reports/{$report->id}/{$filename}";
        $thumbnailPath = "reports/{$report->id}/thumb_{$filename}";

        // Baca gambar asli
        $image = $this->manager->decode($file->getRealPath());

        // ---- Versi display (dikompres, max width 1280px) ----
        $display = clone $image;
        if ($display->width() > self::MAX_WIDTH_DISPLAY) {
            $display->scale(width: self::MAX_WIDTH_DISPLAY);
        }
        $displayEncoded = $display->encode(new JpegEncoder(quality: self::QUALITY_DISPLAY));
        Storage::disk('public')->put($displayPath, (string) $displayEncoded);

        // ---- Versi thumbnail (kecil, untuk list/grid) ----
        $thumb = clone $image;
        $thumb->scale(width: self::MAX_WIDTH_THUMB);
        $thumbEncoded = $thumb->encode(new JpegEncoder(quality: self::QUALITY_THUMB));
        Storage::disk('public')->put($thumbnailPath, (string) $thumbEncoded);

        $sizeKb = (int) round(strlen((string) $displayEncoded) / 1024);

        return ReportImage::create([
            'report_id'      => $report->id,
            'path'           => $displayPath,
            'thumbnail_path' => $thumbnailPath,
            'size_kb'        => $sizeKb,
            'width'          => $display->width(),
            'height'         => $display->height(),
            'order'          => $order,
        ]);
    }

    public function delete(ReportImage $image): void
    {
        Storage::disk('public')->delete([$image->path, $image->thumbnail_path]);
        $image->delete();
    }
}