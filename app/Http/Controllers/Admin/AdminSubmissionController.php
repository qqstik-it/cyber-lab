<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaskSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminSubmissionController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');
        if (!in_array($status, ['pending', 'accepted', 'rejected'], true)) {
            $status = 'pending';
        }

        $submissions = TaskSubmission::with(['user', 'task.level.topic', 'reviewer'])
            ->where('status', $status)
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.submissions.index', compact('submissions', 'status'));
    }

    public function show(TaskSubmission $submission)
    {
        $submission->load(['user', 'task.level.topic', 'reviewer']);
        return view('admin.submissions.show', compact('submission'));
    }

    public function accept(Request $request, TaskSubmission $submission)
    {
        $data = $request->validate([
            'feedback' => ['nullable', 'string', 'max:2000'],
        ]);

        $submission->update([
            'status' => 'accepted',
            'feedback' => $data['feedback'] ?? null,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('admin.submissions.show', $submission);
    }

    public function reject(Request $request, TaskSubmission $submission)
    {
        $data = $request->validate([
            'feedback' => ['nullable', 'string', 'max:2000'],
        ]);

        $submission->update([
            'status' => 'rejected',
            'feedback' => $data['feedback'] ?? null,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('admin.submissions.show', $submission);
    }

    /**
     * Обновить комментарий к уже проверенной сдаче (виден пользователю на странице задания).
     */
    public function updateFeedback(Request $request, TaskSubmission $submission)
    {
        if ($submission->status === 'pending') {
            return redirect()
                ->route('admin.submissions.show', $submission)
                ->withErrors(['feedback' => 'Сначала примите или отклоните сдачу.']);
        }

        $data = $request->validate([
            'feedback' => ['nullable', 'string', 'max:2000'],
        ]);

        $submission->update([
            'feedback' => $data['feedback'] ?? null,
        ]);

        return redirect()
            ->route('admin.submissions.show', $submission)
            ->with('feedback_saved', true);
    }
}

