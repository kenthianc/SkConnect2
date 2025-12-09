<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'title',
        'content',
        'author',
        'notify_members',
        'published_at',
    ];

    protected $casts = [
        'notify_members' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Get icon based on type
     */
    public function getIconAttribute()
    {
        return match($this->type) {
            'EVENT' => 'calendar',
            'ANNOUNCEMENT' => 'megaphone',
            'UPDATE' => 'file',
            'REMINDER' => 'bell',
            default => 'file-text',
        };
    }

    /**
     * Get formatted date
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('F d, Y');
    }

    /**
     * Scope: Published news only
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
            ->orderBy('published_at', 'desc');
    }

    /**
     * Scope: By type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: Recent news (last 30 days)
     */
    public function scopeRecent($query)
    {
        return $query->where('created_at', '>=', now()->subDays(30));
    }
}
