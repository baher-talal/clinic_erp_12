<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doctor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'specialization', 'license_number',
        'bio', 'consultation_fee', 'working_hours', 'is_available',
    ];

    protected $casts = [
        'working_hours' => 'array',
        'consultation_fee' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function getNameAttribute()
    {
        return $this->user->name ?? '';
    }

    public function getTodayAppointmentsCountAttribute()
    {
        return $this->appointments()
            ->whereDate('appointment_date', today())
            ->whereNotIn('status', ['cancelled'])
            ->count();
    }
}
