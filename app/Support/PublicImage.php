<?php

namespace App\Support;

use App\Models\Level;
use App\Models\Topic;
use App\Models\User;

class PublicImage
{
    public static function topic(Topic $topic): string
    {
        return asset(self::topicPath($topic->title));
    }

    public static function level(Level $level): string
    {
        $file = self::levelFile($level->title);
        if ($file !== null) {
            return asset('img/' . $file);
        }

        if ($level->relationLoaded('topic') && $level->topic) {
            return self::topic($level->topic);
        }

        $topic = Topic::find($level->topic_id);

        return asset(self::topicPath($topic?->title ?? ''));
    }

    public static function avatar(User $user): string
    {
        $file = match ($user->role) {
            'admin' => 'admin.png',
            'expert' => 'expert.png',
            default => 'user.png',
        };

        return asset('img/' . $file);
    }

    private static function topicPath(string $title): string
    {
        return 'img/' . self::topicFile($title);
    }

    private static function topicFile(string $title): string
    {
        $titleLower = mb_strtolower($title);

        return match (true) {
            str_contains($titleLower, 'криптография') => 'cripto.png',
            str_contains($titleLower, 'web') || str_contains($titleLower, 'веб') => 'web.png',
            str_contains($titleLower, 'трафик') => 'trafic.png',
            str_contains($titleLower, 'аутентификация') => 'auth.png',
            default => 'logo.png',
        };
    }

    private static function levelFile(string $title): ?string
    {
        $titleLower = mb_strtolower($title);

        return match (true) {
            str_contains($titleLower, 'базов') || str_contains($titleLower, 'easy') => 'easy.png',
            str_contains($titleLower, 'средн') || str_contains($titleLower, 'middle') => 'midle.png',
            str_contains($titleLower, 'продвинут') || str_contains($titleLower, 'hard') => 'hard.png',
            default => null,
        };
    }
}
