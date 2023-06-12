<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Family extends Model
{

    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['photo','name'];

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
        return $this->hasMany(Facility::class);
    }

}
