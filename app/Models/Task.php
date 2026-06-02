<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['level_id', 'level_theme_id', 'title', 'content', 'correct_answer', 'order'];

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function theme()
    {
        return $this->belongsTo(LevelTheme::class, 'level_theme_id');
    }

    public function submissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }
}
