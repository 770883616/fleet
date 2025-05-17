<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    use HasFactory;

    protected $primaryKey = 'data_id';

    protected $fillable = [
        'sensor_id',
        'timestamp',
        'value',
        'location',
        'weather_type',
        'obd_code',
        'heart_rate',
        'blood_pressure',
        'is_alerted'
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'value' => 'json',
        'is_alerted' => 'boolean'
    ];

    // العلاقات
    public function sensor()
    {
        return $this->belongsTo(Sensor::class, 'sensor_id');
    }

    public function alert()
    {
        return $this->hasOne(Alert::class, 'data_id');
    }

    // الوظائف
    public function createAlertIfCritical()
    {
        if ($this->isCritical()) {
            $alert = Alert::create([
                'alert_type' => $this->determineAlertType(),
                'message' => $this->generateAlertMessage(),
                'severity' => 'high',
                'data_id' => $this->data_id
            ]);
            
            $this->update(['is_alerted' => true]);
            return $alert;
        }
        return null;
    }

    protected function isCritical()
    {
        // منطق تحديد إذا كانت البيانات حرجة
        return $this->obd_code === 'P0700' || 
               $this->heart_rate > 120 ||
               $this->weather_type === 'extreme';
    }

    protected function determineAlertType()
    {
        if ($this->obd_code) return 'vehicle_fault';
        if ($this->heart_rate) return 'driver_health';
        if ($this->weather_type) return 'weather_alert';
        return 'general_alert';
    }
}
