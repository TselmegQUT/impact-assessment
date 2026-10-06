<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AssessmentReportController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectDocumentController;
use App\Http\Controllers\ProjectOutputController;
use App\Http\Controllers\ProjectRiskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Main Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    $role = auth()->user()->role?->name;

    return match ($role) {
        'admin' => redirect()
            ->route('admin.dashboard'),

        'analyst' => redirect()
            ->route('analyst.dashboard'),

        'project_officer' => redirect()
            ->route('project_officer.dashboard'),

        'manager' => redirect()
            ->route('manager.dashboard'),

        default => abort(
            403,
            'No valid role has been assigned.'
        ),
    };
})
    ->middleware('auth')
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Project Routes
|--------------------------------------------------------------------------
*/

Route::get(
    '/projects',
    [ProjectController::class, 'index']
)
    ->middleware('auth')
    ->name('projects.index');

Route::middleware([
    'auth',
    'role:admin,project_officer',
])->group(function () {
    Route::get(
        '/projects/create',
        [ProjectController::class, 'create']
    )->name('projects.create');

    Route::post(
        '/projects',
        [ProjectController::class, 'store']
    )->name('projects.store');
});

Route::get(
    '/projects/{project}',
    [ProjectController::class, 'show']
)
    ->middleware('auth')
    ->name('projects.show');

/*
|--------------------------------------------------------------------------
| Project Document Routes
|--------------------------------------------------------------------------
*/

/*
| Every authenticated user can view and download
| documents belonging to a project.
*/

Route::middleware('auth')->group(function () {
    Route::get(
        '/projects/{project}/documents',
        [
            ProjectDocumentController::class,
            'index',
        ]
    )->name('projects.documents.index');

    Route::get(
        '/projects/{project}/documents/{document}/download',
        [
            ProjectDocumentController::class,
            'download',
        ]
    )->name('projects.documents.download');
});

/*
| Admins, Project Officers and Analysts can upload
| and delete project documents.
*/

Route::middleware([
    'auth',
    'role:admin,project_officer,analyst',
])->group(function () {
    Route::post(
        '/projects/{project}/documents',
        [
            ProjectDocumentController::class,
            'store',
        ]
    )->name('projects.documents.store');

    Route::delete(
        '/projects/{project}/documents/{document}',
        [
            ProjectDocumentController::class,
            'destroy',
        ]
    )->name('projects.documents.destroy');
});

/*
|--------------------------------------------------------------------------
| Project Output Routes
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:admin,project_officer,analyst',
])->group(function () {
    Route::get(
        '/projects/{project}/outputs/edit',
        [ProjectOutputController::class, 'edit']
    )->name('projects.outputs.edit');

    Route::put(
        '/projects/{project}/outputs',
        [ProjectOutputController::class, 'update']
    )->name('projects.outputs.update');
});

/*
|--------------------------------------------------------------------------
| Project Risk Routes
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:admin,project_officer,analyst',
])->group(function () {
    Route::get(
        '/projects/{project}/risks/edit',
        [ProjectRiskController::class, 'edit']
    )->name('projects.risks.edit');

    Route::put(
        '/projects/{project}/risks',
        [ProjectRiskController::class, 'update']
    )->name('projects.risks.update');
});

/*
|--------------------------------------------------------------------------
| Assessment Report Routes
|--------------------------------------------------------------------------
*/

/*
| Any authenticated user can view a generated report.
*/

Route::get(
    '/projects/{project}/report',
    [AssessmentReportController::class, 'show']
)
    ->middleware('auth')
    ->name('projects.reports.show');

/*
| Admin, Project Officer and Analyst can generate
| and submit assessment reports.
*/

Route::middleware([
    'auth',
    'role:admin,project_officer,analyst',
])->group(function () {
    Route::post(
        '/projects/{project}/report/generate',
        [
            AssessmentReportController::class,
            'generate',
        ]
    )->name('projects.reports.generate');

    Route::patch(
        '/projects/{project}/report/submit',
        [
            AssessmentReportController::class,
            'submit',
        ]
    )->name('projects.reports.submit');
});

/*
| Only the Manager can approve or reject reports.
*/

Route::middleware([
    'auth',
    'role:manager',
])->group(function () {
    Route::patch(
        '/projects/{project}/report/approve',
        [
            AssessmentReportController::class,
            'approve',
        ]
    )->name('projects.reports.approve');

    Route::patch(
        '/projects/{project}/report/reject',
        [
            AssessmentReportController::class,
            'reject',
        ]
    )->name('projects.reports.reject');
});

/*
|--------------------------------------------------------------------------
| Analyst Dashboard
|--------------------------------------------------------------------------
*/

Route::view('/analyst/dashboard', 'role-dashboard', [
    'title' => 'Analyst Dashboard',

    'tasks' => [
        [
            'title' => 'Review Documents',
            'description' =>
                'Review uploaded project documents.',
        ],
        [
            'title' => 'Validate Data',
            'description' =>
                'Check and correct extracted data.',
        ],
        [
            'title' => 'Run Assessment',
            'description' =>
                'Apply impact assessment formulas.',
        ],
    ],
])
    ->middleware([
        'auth',
        'role:analyst',
    ])
    ->name('analyst.dashboard');

/*
|--------------------------------------------------------------------------
| Project Officer Dashboard
|--------------------------------------------------------------------------
*/

Route::view(
    '/project-officer/dashboard',
    'role-dashboard',
    [
        'title' => 'Project Officer Dashboard',

        'tasks' => [
            [
                'title' => 'Create Projects',
                'description' =>
                    'Register new projects in the system.',
            ],
            [
                'title' => 'Upload Documents',
                'description' =>
                    'Upload PDF and Word documents.',
            ],
            [
                'title' => 'Track Progress',
                'description' =>
                    'Monitor each assessment stage.',
            ],
        ],
    ]
)
    ->middleware([
        'auth',
        'role:project_officer',
    ])
    ->name('project_officer.dashboard');

/*
|--------------------------------------------------------------------------
| Manager Dashboard
|--------------------------------------------------------------------------
*/

Route::view('/manager/dashboard', 'role-dashboard', [
    'title' => 'Manager Dashboard',

    'tasks' => [
        [
            'title' => 'View Results',
            'description' =>
                'Review completed impact assessments.',
        ],
        [
            'title' => 'Review Recommendations',
            'description' =>
                'Review the recommended project actions.',
        ],
        [
            'title' => 'Approve Reports',
            'description' =>
                'Approve final assessment reports.',
        ],
    ],
])
    ->middleware([
        'auth',
        'role:manager',
    ])
    ->name('manager.dashboard');

/*
|--------------------------------------------------------------------------
| Administrator Routes
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:admin',
])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        /*
        | The DashboardController provides:
        | - Total projects
        | - Completed projects
        | - Assessment report count
        | - Active user count
        | - Recent projects
        */

        Route::get(
            '/',
            [DashboardController::class, 'index']
        )->name('dashboard');

        Route::resource(
            'users',
            UserController::class
        )->only([
            'index',
            'create',
            'store',
            'edit',
            'update',
        ]);

        Route::patch(
            'users/{user}/status',
            [
                UserController::class,
                'toggleStatus',
            ]
        )->name('users.status');
    });
