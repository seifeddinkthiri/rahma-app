<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function organizations()
    {
        return $this->hasMany(Organization::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
    public function families()
    {
        return $this->hasMany(Family::class);
    }

    public function members()
    {
        return $this->hasMany(Member::class);
    }
    public function interventions()
    {
        return $this->hasMany(Intervention::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
