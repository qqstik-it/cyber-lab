<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaskSubmission;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $pendingCount = TaskSubmission::where('status', 'pending')->count();
        $recent = TaskSubmission::with(['user', 'task.level.topic'])
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        return view('admin.dashboard', [
            'pendingCount' => $pendingCount,
            'recent' => $recent,
        ]);
    }
}

