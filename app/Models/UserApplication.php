<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserApplication extends Model
{
    use HasFactory;

    protected $table = 'user_application';
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'age',
        'education_level',
        'other_education',
        'graduation_year',
        'institution',
        'company_name',
        'position',
        'start_date',
        'end_date',
        'job_description',
        'skills',
        'resume',
        'stage',
        'job_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id'); // or 'job_listing_id'
    }
}
