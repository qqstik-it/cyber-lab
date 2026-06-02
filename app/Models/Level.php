<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = ['topic_id', 'title', 'subtitle', 'image'];

    public function themes()
    {
        return $this->hasMany(LevelTheme::class)->orderBy('sort_order')->orderBy('id');
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class)->orderBy('order');
    }

    public function tasksRaw()
    {
        return $this->hasMany(Task::class);
    }
}
