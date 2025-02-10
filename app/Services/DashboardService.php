<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    public function getSystemSummary(): array
    {
        return Cache::remember('dashboard_stats', 60, function () {
            return [
                'total_projects' => Project::count(),
                'total_tasks' => Task::count(),
                'tasks_by_status' => Task::selectRaw('status, count(*) as count')
                    ->groupBy('status')
                    ->get()
                    ->mapWithKeys(fn ($item) => [$item['status'] => $item['count']]),
                'total_users' => User::count(),
            ];
        });
    }
}
