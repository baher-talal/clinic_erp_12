<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id', 'patient_id', 'doctor_id',
        'complaint', 'diagnosis', 'treatment_plan', 'notes',
        'weight', 'height', 'blood_pressure', 'temperature', 'pulse',
    ];

    public function appointment() { return $this->belongsTo(Appointment::class); }
    public function patient()     { return $this->belongsTo(Patient::class); }
    public function doctor()      { return $this->belongsTo(Doctor::class); }
    public function prescription(){ return $this->hasOne(Prescription::class); }

    public function getBmiAttribute()
    {
        if ($this->weight && $this->height) {
            $h = $this->height / 100;
            return round($this->weight / ($h * $h), 1);
        }
        return null;
    }
}
