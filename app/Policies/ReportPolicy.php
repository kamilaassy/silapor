<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Report;

class ReportPolicy
{
    public function delete(User $user, Report $report): bool
    {
        // Pemilik laporan boleh hapus, atau admin
        return $user->id === $report->user_id || $user->hasRole('admin');
    }

    public function view(User $user, Report $report): bool
    {
        if ($report->is_public) {
            return true;
        }

        return $user->id === $report->user_id || $user->hasAnyRole(['petugas', 'admin']);
    }
}