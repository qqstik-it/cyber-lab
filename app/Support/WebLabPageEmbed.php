<?php

namespace App\Support;

use App\Models\Level;
use App\Models\Task;

class WebLabPageEmbed
{
    public static function applies(Level $level, ?Task $task): bool
    {
        if (! $task) {
            return false;
        }

        $topicTitle = $level->relationLoaded('topic')
            ? ($level->topic?->title ?? '')
            : (string) $level->topic()->value('title');

        return $topicTitle === 'WEB' && $level->title === 'Базовый уровень';
    }

    public static function embedKey(Task $task): ?string
    {
        return match ($task->title) {
            'Задание 1. Комментарий в коде' => 'comment_near_form',
            'Задание 2. Семантический тег' => 'semantic_heading',
            'Задание 3. Подозрительная ссылка' => 'data_secret_link',
            'Задание 4. Alt у изображения' => 'img_alt',
            'Задание 5. HTML-сущности' => 'html_entities',
            'Задание 6. Скрытое поле формы' => 'hidden_form_field',
            default => null,
        };
    }
}
