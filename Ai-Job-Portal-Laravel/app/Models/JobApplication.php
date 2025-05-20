<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = [
        'job_id',
        'user_id',
        'fullname',
        'email',
        'phone',
        'location',
        'summary',
        'skills',
        'portfolio',
        'linkedin',
        'github',
        'resume',
        'cover_letter',
        'status',
        'notes'
    ];

    // Relationship with Job
    public function job()
    {
        return $this->belongsTo(JobList::class, 'job_id');
    }

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
} 