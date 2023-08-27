<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grant extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'source',
        'family_id',
        'individual_id',



    ];


    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function individual()
    {
        return $this->belongsTo(Individual::class);
    }
}
