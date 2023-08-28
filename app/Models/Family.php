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
        'status',
        'elderlies_number',
        "childrens_number",
        "other_members_number"];

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }



    public function scopeWhereType($query, $type)
    {
        if ($type === 'family' || $type === 'all') {
            return $query;
        }else {
            return $query->whereRaw('1 = 0');
        }
    }




    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('caregiver_phone', 'like', '%' . $search . '%')
                ->orWhere('address', 'like', '%' . $search . '%');
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        })->when($filters['type'] ?? null, function ($query, $type) {
            $query->whereType($type);
        });
    }

    public function interventions()
    {
        return $this->hasMany(Intervention::class);
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
    public function files()
    {
        return $this->hasMany(File::class);
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
