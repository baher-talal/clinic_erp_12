<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_code', 'name', 'national_id', 'gender', 'birth_date',
        'phone', 'phone_emergency', 'email', 'address',
        'blood_type', 'allergies', 'chronic_diseases',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    // Auto-generate patient code
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($patient) {
            $last = static::withTrashed()->latest('id')->first();
            $nextId = $last ? $last->id + 1 : 1;
            $patient->patient_code = 'PAT-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        });
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function getAgeAttribute()
    {
        return $this->birth_date->age;
    }

    public function getGenderLabelAttribute()
    {
        return $this->gender === 'male' ? 'ذكر' : 'أنثى';
    }
}
