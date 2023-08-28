<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimatedReadings extends Model
{
    use HasFactory;
    protected $fillable = [
        'meters_id', 'estimated_reading'
    ];
    public function meters()
    {
        return $this->belongsTo(Meters::class);
    }
}
