<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ReportImage extends Model
{
    protected $fillable = [
        'report_id', 'path', 'thumbnail_path', 'size_kb', 'width', 'height', 'order',
    ];

    protected $appends = ['url', 'thumbnail_url'];

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->path);
    }

    public function getThumbnailUrlAttribute(): string
    {
        return Storage::url($this->thumbnail_path);
    }
}
