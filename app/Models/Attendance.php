<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{ 
    protected $table = 'attendance';
    
    use HasFactory;

    protected $fillable = [
        'event_id',
        'member_id',
        'status',
        'check_in_time',
        'remarks',
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
    ];

    /**
     * Relationship: Event
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Relationship: Member
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Scope: Present only
     */
    public function scopePresent($query)
    {
        return $query->where('status', 'Present');
    }

    /**
     * Scope: By event
     */
    public function scopeByEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    /**
     * Scope: By member
     */
    public function scopeByMember($query, $memberId)
    {
        return $query->where('member_id', $memberId);
    }
}
