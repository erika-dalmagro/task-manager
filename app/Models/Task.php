<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

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
    
}

