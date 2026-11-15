<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medicine extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'name', 'generic_name', 'barcode', 'unit',
        'quantity_in_stock', 'min_stock_alert', 'purchase_price',
        'selling_price', 'expiry_date', 'manufacturer', 'is_active',
    ];

    protected $casts = [
        'expiry_date'    => 'date',
        'purchase_price' => 'decimal:2',
        'selling_price'  => 'decimal:2',
        'is_active'      => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(MedicineCategory::class);
    }

    public function prescriptionItems()
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    public function getLowStockAttribute()
    {
        return $this->quantity_in_stock <= $this->min_stock_alert;
    }

    public function getExpiryStatusAttribute()
    {
        if (!$this->expiry_date) return 'unknown';
        if ($this->expiry_date->isPast()) return 'expired';
        if (now()->diffInDays($this->expiry_date, false) <= 30) return 'expiring_soon';
        return 'valid';
    }
}
