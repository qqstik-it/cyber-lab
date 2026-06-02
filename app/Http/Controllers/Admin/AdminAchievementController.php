<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Services\AchievementService;
use Illuminate\Http\Request;

class AdminAchievementController extends Controller
{
    public function __construct(
        private AchievementService $achievements
    ) {}

    public function index()
    {
        $achievements = Achievement::query()
            ->whereNull('topic_id')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.achievements.index', compact('achievements'));
    }

    public function create()
    {
        return view('admin.achievements.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->achievements->achievementRules());

        Achievement::create([
            'topic_id' => null,
            'title' => $data['title'],
            'description' => $data['description'],
            'icon' => $data['icon'],
            'threshold' => $data['threshold'],
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.achievements.index')->with('success', 'Награда добавлена');
    }

    public function edit(Achievement $achievement)
    {
        return view('admin.achievements.edit', compact('achievement'));
    }

    public function update(Request $request, Achievement $achievement)
    {
        if ($achievement->topic_id !== null) {
            $data = $request->validate($this->achievements->achievementRules());
            $achievement->update([
                'title' => $data['title'],
                'description' => $data['description'],
                'icon' => $data['icon'],
                'threshold' => $data['threshold'],
                'sort_order' => $data['sort_order'] ?? $achievement->sort_order,
            ]);

            return redirect()
                ->route('admin.topics.edit', $achievement->topic_id)
                ->with('success', 'Награда обновлена');
        }

        $data = $request->validate($this->achievements->achievementRules());
        $achievement->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'icon' => $data['icon'],
            'threshold' => $data['threshold'],
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.achievements.index')->with('success', 'Награда обновлена');
    }

    public function destroy(Achievement $achievement)
    {
        $topicId = $achievement->topic_id;
        $achievement->delete();

        if ($topicId !== null) {
            return redirect()
                ->route('admin.topics.edit', $topicId)
                ->with('success', 'Награда удалена');
        }

        return redirect()->route('admin.achievements.index')->with('success', 'Награда удалена');
    }

    public function storeForTopic(Request $request, \App\Models\Topic $topic)
    {
        $data = $request->validate($this->achievements->achievementRules());

        Achievement::create([
            'topic_id' => $topic->id,
            'title' => $data['title'],
            'description' => $data['description'],
            'icon' => $data['icon'],
            'threshold' => $data['threshold'],
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return redirect()
            ->route('admin.topics.edit', $topic)
            ->with('success', 'Награда категории добавлена');
    }
}
