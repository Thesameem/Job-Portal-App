<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'professional_title',
        'location',
        'phone',
        'about',
        'profile_photo',
        'frontend_skills',
        'backend_skills',
        'other_skills',
        'work_experience',
        'education',
        'availability_status',
        'preferred_work_type',
        'notice_period',
        'expected_salary',
        'languages',
        'certifications',
        'github_url',
        'linkedin_url',
        'twitter_url',
        'portfolio_url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'work_experience' => 'array',
        'education' => 'array',
        'languages' => 'array',
        'certifications' => 'array',
    ];
    
    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    /**
     * Get the profile photo URL.
     *
     * @return string|null
     */
    public function getProfilePhotoUrlAttribute()
    {
        if (!$this->profile_photo) {
            return null;
        }
        
        return url('storage/profile-photos/' . $this->profile_photo);
    }

    /**
     * Get the user that owns the profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 