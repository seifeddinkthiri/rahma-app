<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Intervention extends Model
{
    use HasFactory;
    use SoftDeletes;







    protected $fillable = [
        'type',
        'value',
        'intervenor',
        'intervenor_phone',
        'notes',
        'date'

    ];

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    public function scopeWhereType($query, $type)
    {
            return  $query->where('type', $type);

    }




    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('intervenor', 'like', '%'.$search.'%')
            ->orWhere('intervenor_phone', 'like', '%'.$search.'%')
            ;
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        })->when($filters['type'] ?? null, function ($query, $type) {

                $query->where('type', $type);
                })
          ->when($filters['isSolitary'] ?? null, function ($query, $isSolitary) {
            if ($isSolitary =='isSolitary') {
                $query->where('isSolitary', true);
            }
            if ($isSolitary =='isNormal') {
                $query->where('isSolitary', false);
            }

                    });
    }
}
