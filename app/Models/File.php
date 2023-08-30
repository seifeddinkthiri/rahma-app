<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'file',
    ];

    public function intervention()
    {
        return $this->belongsTo(Intervention::class);
    }
    public function family()
    {
        return $this->belongsTo(Family::class);
    }

}
