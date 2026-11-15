<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_record_id', 'patient_id', 'doctor_id',
        'prescription_code', 'instructions', 'status',
    ];

    public function medicalRecord() { return $this->belongsTo(MedicalRecord::class); }
    public function patient()       { return $this->belongsTo(Patient::class); }
    public function doctor()        { return $this->belongsTo(Doctor::class); }
    public function items()         { return $this->hasMany(PrescriptionItem::class); }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending'   => 'بانتظار الصرف',
            'dispensed' => 'تم الصرف',
            'cancelled' => 'ملغية',
            default     => $this->status,
        };
    }
}
