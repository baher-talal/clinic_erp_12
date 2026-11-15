<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Call extends Model
{
    use HasFactory;

    protected $fillable = [
        'call_code', 'patient_id', 'handled_by', 'caller_name', 'caller_phone',
        'type', 'purpose', 'status', 'notes', 'duration_seconds', 'called_at',
    ];

    protected $casts = ['called_at' => 'datetime'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($call) {
            $last = static::latest('id')->first();
            $nextId = $last ? $last->id + 1 : 1;
            $call->call_code = 'CALL-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        });
    }

    public function patient()   { return $this->belongsTo(Patient::class); }
    public function handledBy() { return $this->belongsTo(User::class, 'handled_by'); }
    public function followups() { return $this->hasMany(Followup::class); }

    public function getPurposeLabelAttribute()
    {
        return match($this->purpose) {
            'appointment' => 'حجز موعد',
            'inquiry'     => 'استفسار',
            'complaint'   => 'شكوى',
            'followup'    => 'متابعة',
            'other'       => 'أخرى',
            default       => $this->purpose,
        };
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'answered'        => 'تم الرد',
            'missed'          => 'فائتة',
            'callback_needed' => 'تحتاج رد',
            'resolved'        => 'محلولة',
            default           => $this->status,
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'answered'        => 'success',
            'missed'          => 'danger',
            'callback_needed' => 'warning',
            'resolved'        => 'info',
            default           => 'secondary',
        };
    }

    public function getDurationFormattedAttribute()
    {
        if (!$this->duration_seconds) return '-';
        $m = intdiv($this->duration_seconds, 60);
        $s = $this->duration_seconds % 60;
        return sprintf('%02d:%02d', $m, $s);
    }
}
