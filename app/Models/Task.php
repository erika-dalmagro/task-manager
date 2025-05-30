<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
    ];

    public function scopeFilterStatus($query, $status)
    {
        return $status ? $query->where('status', $status) : $query;
    }

    public function scopeFilterPriority($query, $priority)
    {
        return $priority ? $query->where('priority', $priority) : $query;
    }

    /**
     * Many-to-many: Task ↔ Categories
    */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }
}

