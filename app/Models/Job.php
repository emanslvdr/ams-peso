<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = 'job_listing';

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'organization_id',
        'skills',
        'status',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function applications()
    {
        return $this->hasMany(UserApplication::class, 'job_id');
    }
}
