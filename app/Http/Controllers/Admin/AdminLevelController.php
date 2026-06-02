<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Topic;
use Illuminate\Http\Request;

class AdminLevelController extends Controller
{
    public function create(Topic $topic)
    {
        return view('admin.levels.create', compact('topic'));
    }

    public function store(Request $request, Topic $topic)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        $topic->levels()->create([
            'title' => $data['title'],
            'subtitle' => '',
        ]);

        return redirect()->route('admin.topics.edit', $topic)->with('success', 'Изменения внесены на сервер');
    }

    public function edit(Level $level)
    {
        $level->load([
            'topic',
            'themes' => fn ($q) => $q->orderBy('sort_order')->orderBy('id'),
            'themes.tasks' => fn ($q) => $q->orderBy('order'),
            'tasks' => fn ($q) => $q->orderBy('order'),
        ]);

        return view('admin.levels.edit', compact('level'));
    }

    public function update(Request $request, Level $level)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:2048'],
        ]);

        $image = isset($data['image']) && is_string($data['image']) && trim($data['image']) !== ''
            ? trim($data['image'])
            : null;

        $level->update([
            'title' => $data['title'],
            'image' => $image,
        ]);

        return redirect()->route('admin.levels.edit', $level)->with('success', 'Изменения внесены на сервер');
    }

    public function destroy(Level $level)
    {
        $topic = $level->topic;
        $level->delete();
        return redirect()->route('admin.topics.edit', $topic)->with('success', 'Изменения внесены на сервер');
    }
}

