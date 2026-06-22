<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'report_number', 'user_id', 'category_id', 'status_id', 'assigned_to',
        'title', 'description',
        'latitude', 'longitude', 'address', 'kelurahan', 'kecamatan',
        'is_public',
        'weather_condition', 'weather_temp', 'weather_icon',
    ];

    protected $casts = [
        'is_public'    => 'boolean',
        'latitude'     => 'float',
        'longitude'    => 'float',
        'weather_temp' => 'float',
    ];

    // Auto-generate nomor laporan: SL-2024-0001
    protected static function booted(): void
    {
        static::creating(function (Report $report) {
            $year = now()->year;
            $last = static::whereYear('created_at', $year)
                ->withTrashed()
                ->lockForUpdate()
                ->max('report_number');

            if ($last) {
                $lastNum = (int) substr($last, -4);
                $nextNum = $lastNum + 1;
            } else {
                $nextNum = 1;
            }

            $report->report_number = 'SL-' . $year . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);

            if (! $report->status_id) {
                $report->status_id = Status::where('slug', 'baru-masuk')->value('id');
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ReportImage::class)->orderBy('order');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(ReportHistory::class)->latest();
    }

    public function firstImage(): ?ReportImage
    {
        return $this->images()->first();
    }

    // Scopes
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeByStatus($query, string $slug)
    {
        return $query->whereHas('status', fn($q) => $q->where('slug', $slug));
    }

    public function scopeSearch($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('title', 'like', "%{$keyword}%")
              ->orWhere('description', 'like', "%{$keyword}%")
              ->orWhere('address', 'like', "%{$keyword}%")
              ->orWhere('report_number', 'like', "%{$keyword}%");
        });
    }
}
