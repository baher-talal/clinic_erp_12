<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Followup extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'followup_code', 'patient_id', 'call_id', 'assigned_to', 'created_by',
        'title', 'description', 'type', 'priority', 'status', 'due_date',
        'resolution_notes', 'completed_at',
    ];

    protected $casts = [
        'due_date'     => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($f) {
            $last = static::withTrashed()->latest('id')->first();
            $nextId = $last ? $last->id + 1 : 1;
            $f->followup_code = 'FLW-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        });
    }

    public function patient()    { return $this->belongsTo(Patient::class); }
    public function call()       { return $this->belongsTo(Call::class); }
    public function assignedTo() { return $this->belongsTo(User::class, 'assigned_to'); }
    public function createdBy()  { return $this->belongsTo(User::class, 'created_by'); }

    public function getPriorityLabelAttribute()
    {
        return match($this->priority) {
            'low'    => 'منخفض',
            'medium' => 'متوسط',
            'high'   => 'عالي',
            'urgent' => 'عاجل',
            default  => $this->priority,
        };
    }

    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'low'    => 'secondary',
            'medium' => 'info',
            'high'   => 'warning',
            'urgent' => 'danger',
            default  => 'secondary',
        };
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending'     => 'معلق',
            'in_progress' => 'جارٍ',
            'completed'   => 'مكتمل',
            'cancelled'   => 'ملغي',
            default       => $this->status,
        };
    }

    public function getIsOverdueAttribute()
    {
        return $this->status !== 'completed' && $this->due_date->isPast();
    }
}
