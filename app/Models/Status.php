<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    protected $fillable = ['name', 'slug', 'color_hex', 'bg_hex', 'description', 'order'];

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(ReportHistory::class);
    }
}
