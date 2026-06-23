<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'title', 'description', 'status', 'priority', 'due_date'];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function project()        // Issue i përket një projekti
    {
        return $this->belongsTo(Project::class);
    }

    public function comments()       // Issue ka shumë komente
    {
        return $this->hasMany(Comment::class);
    }

    public function tags()           // Issue ka shumë tags (many-to-many)
    {
        return $this->belongsToMany(Tag::class);
    }
}