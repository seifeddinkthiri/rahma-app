<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;


    protected $fillable = ['Sanitation','electricity','water','ventilation'];


    public function family()
    {
        return $this->belongsTo(Family::class);
    }


}
