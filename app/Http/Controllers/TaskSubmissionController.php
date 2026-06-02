<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskSubmissionController extends Controller
{
    public function submit(Request $request, Task $task)
    {
        $data = $request->validate([
            'answer' => ['nullable', 'string', 'max:2000'],
        ]);

        $userId = Auth::id();
        $answer = $data['answer'] ?? null;

        $submission = TaskSubmission::firstOrNew([
            'task_id' => $task->id,
            'user_id' => $userId,
        ]);

        $submission->answer = $answer;
        $submission->feedback = null;
        $submission->reviewed_by = null;
        $submission->reviewed_at = null;

        $correct = $task->correct_answer;

        // Если у задания есть эталонный ответ — проверяем автоматически.
        if (!is_null($correct) && trim((string) $correct) !== '') {
            $norm = static function (?string $v): string {
                return mb_strtolower(trim((string) $v));
            };

            $submission->status = $norm($answer) === $norm($correct) ? 'accepted' : 'rejected';
        } else {
            // Иначе отдаём на ручную проверку администратору.
            $submission->status = 'pending';
        }

        $submission->save();

        return back()->with('submission_saved', true);
    }
}

