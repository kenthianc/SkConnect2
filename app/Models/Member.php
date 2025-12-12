<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'member_id',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone',
        'birthdate',
        'age',
        'gender',
        'purok',
        'address',
        'guardian_name',
        'guardian_contact',
        'role',
        'registered_via',
        'date_joined',
        'is_active',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'date_joined' => 'date',
        'is_active' => 'boolean',
    ];

    protected $appends = ['name'];

    /**
     * Get full name attribute
     */
    public function getNameAttribute()
    {
        $middle = $this->middle_name ? " {$this->middle_name} " : ' ';
        return "{$this->first_name}{$middle}{$this->last_name}";
    }

    /**
     * Generate unique member ID
     */
    public static function generateMemberId()
    {
        $year = '25'; // Fixed year "25" for 2025

        // Generate a random number between 10000 and 99999 (5 digits)
        $newNumber = random_int(10000, 99999);

        // Check if the generated number already exists in the database
        while (static::where('member_id', "{$year}-{$newNumber}")->exists()) {
            // If it exists, generate a new one
            $newNumber = random_int(10000, 99999);
        }

        return $year . '-' . $newNumber;
    }

    /**
     * Relationship: Attendance records
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get attendance rate for this member
     */
    public function getAttendanceRateAttribute()
    {
        $total = $this->attendances()->count();
        if ($total === 0) {
            return 0;
        }

        $present = $this->attendances()->where('status', 'Present')->count();
        return round(($present / $total) * 100, 1);
    }

    /**
     * Scope: Active members only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    
    public function scopeSearch(Builder $query, string $search): Builder
    {
        $search = trim($search);

        return $query->where(function ($q) use ($search) {
            $q->where('member_id', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
              ->orWhereRaw("CONCAT(first_name, ' ', middle_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
        });
    }

    public function scopeByRole(Builder $query, string $role): Builder
    {
        return $query->where('role', $role); // Member / Officer
    }

    public function scopeByPurok(Builder $query, string $purok): Builder
    {
        return $query->where('purok', $purok);
    }
}
