<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Services\AchievementService;
use Illuminate\Http\Request;

class AdminTopicController extends Controller
{
    public function __construct(
        private AchievementService $achievements
    ) {}
    public function index()
    {
        $topics = Topic::withCount('levels')->orderBy('id')->get();
        return view('admin.topics.index', compact('topics'));
    }

    public function create()
    {
        return view('admin.topics.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'image' => ['required', 'string', 'max:2048'],
        ]);

        $topic = Topic::create([
            'title' => $data['title'],
            'author' => $data['author'],
            'image' => $data['image'],
            'progress_current' => 0,
            'progress_total' => 0,
        ]);

        $this->achievements->syncTopicAchievements($topic, $request->input('achievements', []));

        return redirect()->route('admin.topics.index')->with('success', 'Изменения внесены на сервер');
    }

    public function edit(Topic $topic)
    {
        $topic->load([
            'achievements',
            'levels' => fn ($q) => $q->orderBy('id'),
            'levels.themes' => fn ($q) => $q->orderBy('sort_order')->orderBy('id'),
            'levels.themes.tasks' => fn ($q) => $q->orderBy('order'),
            'levels.tasks' => fn ($q) => $q->orderBy('order'),
        ]);

        return view('admin.topics.edit', compact('topic'));
    }

    public function update(Request $request, Topic $topic)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'image' => ['required', 'string', 'max:2048'],
        ]);

        $topic->update([
            'title' => $data['title'],
            'author' => $data['author'],
            'image' => $data['image'],
        ]);

        return redirect()->route('admin.topics.edit', $topic)->with('success', 'Изменения внесены на сервер');
    }

    public function destroy(Topic $topic)
    {
        $topic->delete();
        return redirect()->route('admin.topics.index')->with('success', 'Изменения внесены на сервер');
    }
}

