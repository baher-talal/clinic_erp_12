<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Models\Prescription;
use App\Models\TreasuryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PharmacyController extends Controller
{
    public function index()
    {
        $medicines = Medicine::with('category')
            ->where('is_active', true)
            ->paginate(20);

        return view('pharmacy.index', compact('medicines'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'      => 'required|exists:medicine_categories,id',
            'name'             => 'required|string|max:255',
            'generic_name'     => 'nullable|string|max:255',
            'barcode'          => 'nullable|unique:medicines,barcode',
            'unit'             => 'required|string|max:50',
            'quantity_in_stock'=> 'required|integer|min:0',
            'min_stock_alert'  => 'required|integer|min:1',
            'purchase_price'   => 'required|numeric|min:0',
            'selling_price'    => 'required|numeric|min:0',
            'expiry_date'      => 'nullable|date|after:today',
            'manufacturer'     => 'nullable|string|max:255',
        ]);

        Medicine::create($validated);

        return redirect()->route('pharmacy.index')
            ->with('success', 'تم إضافة الدواء بنجاح');
    }

    public function dispensePrescription(Request $request, Prescription $prescription)
    {
        DB::transaction(function () use ($prescription, $request) {
            $totalCost = 0;

            foreach ($prescription->items as $item) {
                $medicine = $item->medicine;
                // خصم الكمية من المخزن
                $medicine->decrement('quantity_in_stock', 1);
                $totalCost += $medicine->selling_price;
            }

            $prescription->update(['status' => 'dispensed']);

            // تسجيل في الخزنة
            TreasuryTransaction::create([
                'transaction_code' => 'TRX-PHARM-' . now()->format('YmdHis'),
                'created_by'       => auth()->id(),
                'type'             => 'income',
                'category'         => 'medicine_sale',
                'amount'           => $totalCost,
                'payment_method'   => $request->payment_method ?? 'cash',
                'description'      => 'صرف روشتة - ' . $prescription->prescription_code,
                'related_id'       => $prescription->id,
                'related_type'     => Prescription::class,
                'transaction_date' => today(),
            ]);
        });

        return back()->with('success', 'تم صرف الروشتة بنجاح');
    }

    public function lowStock()
    {
        $medicines = Medicine::with('category')
            ->where('is_active', true)
            ->whereRaw('quantity_in_stock <= min_stock_alert')
            ->get();

        return view('pharmacy.low-stock', compact('medicines'));
    }
}
