<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Individual extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasApiTokens;

    protected $fillable = [
        'photo',
        'name',
        'phone',
        'address',
        'cin',
        'gender',
        'birth_date',
        'birth_city',
        'social_status',
        'monthly_income',
        'education_level',
        'job',
        'job_place',
    ];


    public function files()
    {
        return $this->hasMany(File::class);
    }
    public function interventions()
    {
        return $this->hasMany(Intervention::class);
    }
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'like', '%'.$search.'%')
            ->orWhere('phone', 'like', '%'.$search.'%')
            ->orWhere('address', 'like', '%'.$search.'%')
            ->orWhere('cin', 'like', '%'.$search.'%')

            ;
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }


    public function healthStatus()
    {
        return $this->hasOne(HealthStatus::class);
    }



    public function home()
    {
        return $this->hasOne(Home::class);
    }
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function facilities()
    {
        return $this->hasOne(Facility::class);
    }
    public static function boot()
    {
        parent::boot();

        static::created(function ($family) {
            $family->facilities()->create([
                'Sanitation'=>false,
                'electricity'=>false,
                'ventilation'=>false,
                'water'=>false,

            ]);
        });
    }

}
