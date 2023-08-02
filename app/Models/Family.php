<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Family extends Model
{

    use HasFactory;
    use SoftDeletes;
    use HasApiTokens;

    protected $fillable = [
        'photo',
        'name',
        'caregiver_phone',
        'address',
        'wife',
        'husband',
        'elderlies_number',
        "childrens_number",
        "other_members_number"];

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('caregiver_phone', 'like', '%'.$search.'%')
            ->orWhere('address', 'like', '%'.$search.'%')

            ;
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }



    public function interventions()
    {
        return $this->belongsToMany(Intervention::class);
    }

    public function home()
    {
        return $this->hasOne(Home::class);
    }

    public function members()
    {
        return $this->hasMany(Member::class);
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
                'Sanitation' => false,
                'electricity' => false,
                'ventilation' => false,
                'water' => false,

            ]);
        });
    }
}
