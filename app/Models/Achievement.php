<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Achievement extends Model
{
    protected $fillable = [
        'topic_id',
        'title',
        'description',
        'icon',
        'threshold',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'threshold' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function isGlobal(): bool
    {
        return $this->topic_id === null;
    }
}
