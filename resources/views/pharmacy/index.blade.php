@extends('layouts.app')
@section('title', 'الصيدلية والمخزن')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">الصيدلية</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="fas fa-pills me-2 text-primary"></i>إدارة الأدوية والمخزن</h5>
    <div class="d-flex gap-2">
        <a href="{{ route('pharmacy.low-stock') }}" class="btn btn-warning btn-sm">
            <i class="fas fa-exclamation-triangle me-1"></i> تنبيهات المخزون
        </a>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addMedicineModal">
            <i class="fas fa-plus me-1"></i> إضافة دواء
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>اسم الدواء</th>
                        <th>الفئة</th>
                        <th>الوحدة</th>
                        <th>المخزون</th>
                        <th>سعر البيع</th>
                        <th>انتهاء الصلاحية</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($medicines as $med)
                    <tr class="{{ $med->low_stock ? 'table-warning' : '' }}">
                        <td>
                            <div class="fw-semibold">{{ $med->name }}</div>
                            @if($med->generic_name)
                                <small class="text-muted">{{ $med->generic_name }}</small>
                            @endif
                        </td>
                        <td>{{ $med->category->name }}</td>
                        <td>{{ $med->unit }}</td>
                        <td>
                            <span class="badge {{ $med->low_stock ? 'bg-danger' : 'bg-success' }}">
                                {{ $med->quantity_in_stock }}
                            </span>
                            @if($med->low_stock)
                                <small class="text-danger d-block">⚠ أقل من الحد الأدنى ({{ $med->min_stock_alert }})</small>
                            @endif
                        </td>
                        <td>{{ number_format($med->selling_price, 2) }} ج.م</td>
                        <td>
                            @if($med->expiry_date)
                                <span class="{{ $med->expiry_status === 'expired' ? 'text-danger' : ($med->expiry_status === 'expiring_soon' ? 'text-warning' : '') }}">
                                    {{ $med->expiry_date->format('m/Y') }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($med->expiry_status === 'expired')
                                <span class="badge bg-danger">منتهي الصلاحية</span>
                            @elseif($med->expiry_status === 'expiring_soon')
                                <span class="badge bg-warning text-dark">ينتهي قريباً</span>
                            @else
                                <span class="badge bg-success">سليم</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-box-open fa-3x mb-3 d-block"></i>
                            لا توجد أدوية مسجلة
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $medicines->links() }}</div>
    </div>
</div>

{{-- Modal إضافة دواء --}}
<div class="modal fade" id="addMedicineModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">إضافة دواء جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('pharmacy.medicines.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">اسم الدواء *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">الاسم العلمي</label>
                            <input type="text" name="generic_name" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">الفئة *</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- اختر --</option>
                                @foreach(\App\Models\MedicineCategory::all() as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">الوحدة *</label>
                            <select name="unit" class="form-select" required>
                                <option value="علبة">علبة</option>
                                <option value="شريط">شريط</option>
                                <option value="زجاجة">زجاجة</option>
                                <option value="حبة">حبة</option>
                                <option value="أمبول">أمبول</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">الكمية *</label>
                            <input type="number" name="quantity_in_stock" class="form-control" min="0" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">الحد الأدنى *</label>
                            <input type="number" name="min_stock_alert" class="form-control" value="10" min="1" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">سعر الشراء *</label>
                            <input type="number" name="purchase_price" class="form-control" min="0" step="0.01" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">سعر البيع *</label>
                            <input type="number" name="selling_price" class="form-control" min="0" step="0.01" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">تاريخ الانتهاء</label>
                            <input type="date" name="expiry_date" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">الشركة المصنّعة</label>
                            <input type="text" name="manufacturer" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> حفظ الدواء
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
