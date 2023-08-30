<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Home extends Model
{


    use HasFactory;

    protected $fillable = [
        'status',
        'allocation_price',
        'desciption',

    ];


    public function family()
    {
        return $this->belongsTo(Family::class);
    }



}
