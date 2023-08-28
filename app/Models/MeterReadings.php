<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeterReadings extends Model
{
    use HasFactory;
    protected $fillable = [
        'meters_id', 'reading_value', 'reading_date'
    ];
    public function meters()
    {
        return $this->belongsTo(Meters::class); 
    }
}
