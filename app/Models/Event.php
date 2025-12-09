<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'category',
        'date',
        'start_time',
        'end_time',
        'location',
        'venue',
        'target_participants',
        'max_capacity',
        'registration_deadline',
        'status',
        'cover_image',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'registration_deadline' => 'date',
    ];

    /**
     * Relationship: Attendance records
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

     public function attendance()
    {
        return $this->attendances();
    }

    /**
     * Get registered participants count
     */
    public function getRegisteredCountAttribute()
    {
        return $this->attendance()->count();
    }

    /**
     * Get present count
     */
    public function getPresentCountAttribute()
    {
        return $this->attendance()->where('status', 'Present')->count();
    }

    /**
     * Get attendance rate
     */
    public function getAttendanceRateAttribute()
    {
        $total = $this->attendance()->count();
        if ($total === 0) return 0;

        $present = $this->attendance()->where('status', 'Present')->count();
        return round(($present / $total) * 100, 1);
    }

    /**
     * Check if event is past
     */
    public function getIsPastAttribute()
    {
        return $this->date->isPast();
    }

    /**
     * Check if event is today
     */
    public function getIsTodayAttribute()
    {
        return $this->date->isToday();
    }

    /**
     * Auto-update status based on date
     */
    public static function updateEventStatuses()
    {
        // Set to Ongoing if date is today
        static::whereDate('date', Carbon::today())
            ->where('status', 'Upcoming')
            ->update(['status' => 'Ongoing']);

        // Set to Completed if date has passed
        static::whereDate('date', '<', Carbon::today())
            ->whereIn('status', ['Upcoming', 'Ongoing'])
            ->update(['status' => 'Completed']);
    }

    /**
     * Scope: Upcoming events
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'Upcoming')
            ->whereDate('date', '>=', Carbon::today())
            ->orderBy('date', 'asc');
    }

    /**
     * Scope: Ongoing events
     */
    public function scopeOngoing($query)
    {
        return $query->where('status', 'Ongoing');
    }

    /**
     * Scope: Completed events
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }

    /**
     * Scope: By category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
