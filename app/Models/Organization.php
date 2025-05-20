<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    protected $fillable = [
        'name',
    ];
}
