<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meters extends Model
{
    use HasFactory;
    protected $fillable = [
        'mpxn', 'installation_date', 'meter_type', 'estimated_annual_consumption'
    ];
    public function meterreadings()
    {
        return $this->hasMany(MeterReadings::class);
    }

    public function estimatedreadings()
    {
        return $this->hasMany(EstimatedReadings::class);
    }
}
