<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

class Member extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasApiTokens;




    protected $fillable = [
        'name',
        'address',
        'cin',
        'phone',
        'photo',
        'birth_date',
        'birth_city',
        'social_status',
        'kinship',
        'caregiver',
        'education_level',
        'job',
        'job_place',
        'family_id',





    ];
    public static function boot()
    {
        parent::boot();

        static::created(function ($member) {

            $member->grant()->create([
                'source'=>null,
                'value'=>null,
            ]);
        });
    }

    public function grant()
    {
        return $this->hasOne(Grant::class);
    }


    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'like', '%'.$search.'%');
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



    public function family()
    {
        return $this->belongsTo(Family::class);
    }
}
