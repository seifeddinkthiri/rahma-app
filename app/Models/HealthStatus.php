<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthStatus extends Model
{

    use HasFactory;

    protected $fillable = [
        'good',
        'disease',
        'disability',
        'disability_card_number',
        'member_id',


    ];


    public function member()
    {
        return $this->belongsTo(Member::class);
    }


}
