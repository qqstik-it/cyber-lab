<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelTheme extends Model
{
    protected $fillable = ['level_id', 'title', 'sort_order'];

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class)->orderBy('order');
    }
}
