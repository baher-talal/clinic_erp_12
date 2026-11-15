<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TreasuryTransaction extends Model
{
    protected $fillable = [
        'transaction_code', 'created_by', 'type', 'category',
        'amount', 'payment_method', 'description',
        'related_id', 'related_type', 'transaction_date',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount'           => 'decimal:2',
    ];

    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
    public function related()   { return $this->morphTo(); }

    public function getTypeLabelAttribute()
    {
        return $this->type === 'income' ? 'إيراد' : 'مصروف';
    }

    public function getCategoryLabelAttribute()
    {
        return match($this->category) {
            'appointment_fee' => 'رسوم كشف',
            'medicine_sale'   => 'مبيعات صيدلية',
            'salary'          => 'رواتب',
            'rent'            => 'إيجار',
            'utilities'       => 'مرافق',
            default           => $this->category,
        };
    }
}
