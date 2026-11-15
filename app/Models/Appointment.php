<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'appointment_code', 'patient_id', 'doctor_id', 'created_by',
        'appointment_date', 'duration_minutes', 'status', 'type',
        'fee', 'payment_status', 'notes',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'fee' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($appointment) {
            $last = static::withTrashed()->latest('id')->first();
            $nextId = $last ? $last->id + 1 : 1;
            $appointment->appointment_code = 'APT-' . date('Y') . '-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
        });
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending'     => 'قيد الانتظار',
            'confirmed'   => 'مؤكد',
            'in_progress' => 'جارٍ',
            'completed'   => 'مكتمل',
            'cancelled'   => 'ملغي',
            default       => $this->status,
        };
    }

    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'new'       => 'كشف جديد',
            'follow_up' => 'متابعة',
            'emergency' => 'طارئ',
            default     => $this->type,
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending'     => 'warning',
            'confirmed'   => 'info',
            'in_progress' => 'primary',
            'completed'   => 'success',
            'cancelled'   => 'danger',
            default       => 'secondary',
        };
    }
}
