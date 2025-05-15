<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobList extends Model
{    protected $fillable = [
        'user_id',
        'company_name',
        'company_logo',
        'company_website',
        'company_size',
        'title',
        'type',
        'location',
        'salary_range',
        'description',
        'responsibilities',
        'experience_level',
        'education_level',
        'required_skills',
        'preferred_skills',
        'benefits',
        'application_deadline',
        'contact_email',
        'status'
    ];
}
