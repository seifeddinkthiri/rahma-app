<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name', 'description', 'date', 'deadline', 'status',
    ];


    public function interventions()
    {
        return $this->hasMany(Intervention::class);
    }
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }






    public function scopeWhereStatus($query, $status)
    {
        if ($status == 'completed') {
            return  $query->where('status', true);
        } else if ($status == 'ongoing') {
            return  $query->where('status', false);
        }

    }




    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'like', '%'.$search.'%')
            ->orWhere('description', 'like', '%'.$search.'%')
            ;
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        })->when($filters['status'] ?? null, function ($query, $status) {
            if ($status == 'completed') {
                $query->where('status', true); // قيد التنفيذ
            } else if ($status == 'ongoing') {
                $query->where('status', false); // مكتمل
            }         })
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
