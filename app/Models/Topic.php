<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = ['id', 'title', 'author', 'image', 'progress_current', 'progress_total'];

    public function levels()
    {
        return $this->hasMany(Level::class);
    }
}
