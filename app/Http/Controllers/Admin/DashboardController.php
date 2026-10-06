<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssessmentReport;
use App\Models\Project;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $statistics = [
            'total_projects' => Project::query()
                ->count(),

            'completed_projects' => Project::query()
                ->where('status', 'completed')
                ->count(),

            'total_reports' => AssessmentReport::query()
                ->count(),

            'active_users' => User::query()
                ->where('is_active', true)
                ->count(),
        ];

        $recentProjects = Project::query()
            ->latest()
            ->limit(5)
            ->get();

        return view(
            'admin.dashboard',
            compact(
                'statistics',
                'recentProjects'
            )
        );
    }
}
