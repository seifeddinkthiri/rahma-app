<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;


    protected $fillable = [
       'title', 'Value',
    ];
    public function family()
    {
        return $this->belongsTo(Family::class);
    }

}
