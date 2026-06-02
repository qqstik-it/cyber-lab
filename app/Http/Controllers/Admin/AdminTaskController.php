<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminTaskController extends Controller
{
    public function create(Level $level)
    {
        $level->load(['topic', 'themes' => fn ($q) => $q->orderBy('sort_order')->orderBy('id')]);

        return view('admin.tasks.create', compact('level'));
    }

    public function store(Request $request, Level $level)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'correct_answer' => ['nullable', 'string', 'max:255'],
            'order' => ['nullable', 'integer', 'min:0'],
            'level_theme_id' => [
                'nullable',
                'integer',
                Rule::exists('level_themes', 'id')->where(fn ($q) => $q->where('level_id', $level->id)),
            ],
        ]);

        $level->tasksRaw()->create([
            'title' => $data['title'],
            'content' => $data['content'],
            'correct_answer' => $data['correct_answer'] ?? null,
            'order' => $data['order'] ?? 0,
            'level_theme_id' => $data['level_theme_id'] ?? null,
        ]);

        return redirect()->route('admin.levels.edit', $level)->with('success', 'Изменения внесены на сервер');
    }

    public function edit(Task $task)
    {
        $task->load([
            'level.topic',
            'level.themes' => fn ($q) => $q->orderBy('sort_order')->orderBy('id'),
        ]);

        return view('admin.tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $level = $task->level;

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'correct_answer' => ['nullable', 'string', 'max:255'],
            'order' => ['nullable', 'integer', 'min:0'],
            'level_theme_id' => [
                'nullable',
                'integer',
                Rule::exists('level_themes', 'id')->where(fn ($q) => $q->where('level_id', $level->id)),
            ],
        ]);

        $task->update([
            'title' => $data['title'],
            'content' => $data['content'],
            'correct_answer' => $data['correct_answer'] ?? null,
            'order' => $data['order'] ?? 0,
            'level_theme_id' => $data['level_theme_id'] ?? null,
        ]);

        return redirect()->route('admin.tasks.edit', $task)->with('success', 'Изменения внесены на сервер');
    }

    public function destroy(Task $task)
    {
        $level = $task->level;
        $task->delete();
        return redirect()->route('admin.levels.edit', $level)->with('success', 'Изменения внесены на сервер');
    }
}

