<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Project extends Model
{
    protected $guarded = [];

    public $old = [];

    public function path()
    {
        return "/projects/{$this->id}";
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function addTask($body)
    {
        return $this->tasks()->create(compact('body'));
    }

    public function activity()
    {
        return $this->hasMany(Activity::class)->latest();
    }

    public function recordActivity($description)
    {
        $old = Arr::except($this->old, ['created_at', 'updated_at']);
        $new = Arr::except($this->getAttributes(), ['created_at', 'updated_at']);

        $this->activity()->create([
            'description' => $description,
            'changes'     => [
                'before' => array_diff($old, $new),
                'after'  => array_diff($new, $old)
            ]
        ]);
    }
}
