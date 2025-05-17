<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    use HasFactory;

    protected $fillable = [
        'truck_name',
        'plate_number',
        'chassis_number',
        'vehicle_status',
        'company_id'
    ];

    // العلاقات
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function drivers()
    {
        return $this->belongsToMany(Driver::class, 'driver_truck')
                  ->withTimestamps();
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'truck_id');
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class, 'truck_id');
    }

    public function accidents()
    {
        return $this->hasMany(Accident::class, 'truck_id');
    }

    // الوظائف
    public function assignDriver(Driver $driver)
    {
        $this->drivers()->attach($driver);
        return $this;
    }

    public function updateStatus($status)
    {
        $this->update(['vehicle_status' => $status]);
        
        if ($status === 'maintenance') {
            $this->drivers->each->notifications()->create([
                'message' => "الشاحنة {$this->truck_name} تحت الصيانة",
                'is_read' => false
            ]);
        }
        
        return $this;
    }

    public function getAssignedShipments()
    {
        return $this->shipments()->with('destination')->get();
    }
}
