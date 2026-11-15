<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\{User, Doctor, Patient, Medicine, MedicineCategory, Role, Permission};

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Permissions ──────────────────────────────────────────────
        $permissions = [
            // patients
            ['name'=>'patients.view',   'label'=>'عرض المرضى',       'group'=>'المرضى'],
            ['name'=>'patients.create', 'label'=>'إضافة مريض',       'group'=>'المرضى'],
            ['name'=>'patients.edit',   'label'=>'تعديل بيانات مريض','group'=>'المرضى'],
            ['name'=>'patients.delete', 'label'=>'حذف مريض',         'group'=>'المرضى'],
            // appointments
            ['name'=>'appointments.view',   'label'=>'عرض المواعيد',  'group'=>'المواعيد'],
            ['name'=>'appointments.create', 'label'=>'حجز موعد',      'group'=>'المواعيد'],
            ['name'=>'appointments.edit',   'label'=>'تعديل موعد',    'group'=>'المواعيد'],
            ['name'=>'appointments.pay',    'label'=>'تسجيل الدفع',   'group'=>'المواعيد'],
            // records
            ['name'=>'records.view',   'label'=>'عرض السجلات الطبية','group'=>'السجلات الطبية'],
            ['name'=>'records.create', 'label'=>'إنشاء سجل طبي',     'group'=>'السجلات الطبية'],
            // pharmacy
            ['name'=>'pharmacy.view',    'label'=>'عرض الصيدلية',    'group'=>'الصيدلية'],
            ['name'=>'pharmacy.manage',  'label'=>'إدارة الأدوية',   'group'=>'الصيدلية'],
            ['name'=>'pharmacy.dispense','label'=>'صرف الروشتات',    'group'=>'الصيدلية'],
            // treasury
            ['name'=>'treasury.view',   'label'=>'عرض الخزنة',       'group'=>'الخزنة'],
            ['name'=>'treasury.manage', 'label'=>'إدارة المعاملات',  'group'=>'الخزنة'],
            // cs
            ['name'=>'cs.calls',     'label'=>'إدارة المكالمات', 'group'=>'خدمة العملاء'],
            ['name'=>'cs.followups', 'label'=>'إدارة المتابعات', 'group'=>'خدمة العملاء'],
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p['name']], $p);
        }

        // ── 2. Roles ─────────────────────────────────────────────────
        $rolesData = [
            ['name'=>'admin',            'label'=>'مدير النظام',    'color'=>'danger',    'perms'=>[]],  // all
            ['name'=>'doctor',           'label'=>'طبيب',           'color'=>'primary',   'perms'=>['patients.view','appointments.view','records.view','records.create','pharmacy.view']],
            ['name'=>'receptionist',     'label'=>'استقبال',        'color'=>'success',   'perms'=>['patients.view','patients.create','patients.edit','appointments.view','appointments.create','appointments.edit','appointments.pay']],
            ['name'=>'customer_service', 'label'=>'خدمة عملاء',    'color'=>'info',      'perms'=>['patients.view','appointments.view','cs.calls','cs.followups']],
            ['name'=>'pharmacist',       'label'=>'صيدلاني',        'color'=>'warning',   'perms'=>['pharmacy.view','pharmacy.manage','pharmacy.dispense','patients.view']],
            ['name'=>'accountant',       'label'=>'محاسب',          'color'=>'secondary', 'perms'=>['treasury.view','treasury.manage','appointments.view']],
        ];

        foreach ($rolesData as $rd) {
            $role = Role::firstOrCreate(['name' => $rd['name']], ['label' => $rd['label'], 'color' => $rd['color']]);
            if (!empty($rd['perms'])) {
                $permIds = Permission::whereIn('name', $rd['perms'])->pluck('id');
                $role->permissions()->sync($permIds);
            }
        }

        $adminRole   = Role::where('name','admin')->first();
        $doctorRole  = Role::where('name','doctor')->first();
        $csRole      = Role::where('name','customer_service')->first();
        $recepRole   = Role::where('name','receptionist')->first();
        $pharmRole   = Role::where('name','pharmacist')->first();
        $accountRole = Role::where('name','accountant')->first();

        // ── 3. Users ──────────────────────────────────────────────────
        $admin = User::firstOrCreate(['email'=>'admin@admin.com'], [
            'name'=>'مدير النظام', 'password'=>Hash::make('admin'),
            'phone'=>'01000000000', 'job_title'=>'مدير', 'department'=>'الإدارة',
            'joined_date'=>'2024-01-01', 'is_active'=>true,
        ]);
        $admin->roles()->syncWithoutDetaching([$adminRole->id]);

        $drAhmed = User::firstOrCreate(['email'=>'dr.ahmed@admin.com'], [
            'name'=>'أحمد محمد علي', 'password'=>Hash::make('admin'),
            'phone'=>'01011111111', 'job_title'=>'طبيب عام', 'department'=>'العيادة الداخلية',
            'joined_date'=>'2024-02-01', 'is_active'=>true,
        ]);
        $drAhmed->roles()->syncWithoutDetaching([$doctorRole->id]);

        $drSara = User::firstOrCreate(['email'=>'dr.sara@admin.com'], [
            'name'=>'سارة حسن إبراهيم', 'password'=>Hash::make('admin'),
            'phone'=>'01022222222', 'job_title'=>'طبيبة أطفال', 'department'=>'عيادة الأطفال',
            'joined_date'=>'2024-03-01', 'is_active'=>true,
        ]);
        $drSara->roles()->syncWithoutDetaching([$doctorRole->id]);

        $receptionist = User::firstOrCreate(['email'=>'reception@admin.com'], [
            'name'=>'نور الهدى محمود', 'password'=>Hash::make('admin'),
            'phone'=>'01033333333', 'job_title'=>'أخصائية استقبال', 'department'=>'الاستقبال',
            'joined_date'=>'2024-04-01', 'is_active'=>true,
        ]);
        $receptionist->roles()->syncWithoutDetaching([$recepRole->id]);

        $csAgent = User::firstOrCreate(['email'=>'cs@admin.com'], [
            'name'=>'محمد عبد العزيز', 'password'=>Hash::make('admin'),
            'phone'=>'01044444444', 'job_title'=>'أخصائي خدمة عملاء', 'department'=>'خدمة العملاء',
            'joined_date'=>'2024-05-01', 'is_active'=>true,
        ]);
        $csAgent->roles()->syncWithoutDetaching([$csRole->id]);

        $pharmacist = User::firstOrCreate(['email'=>'pharmacy@admin.com'], [
            'name'=>'هالة صالح', 'password'=>Hash::make('admin'),
            'phone'=>'01055555555', 'job_title'=>'صيدلانية', 'department'=>'الصيدلية',
            'joined_date'=>'2024-06-01', 'is_active'=>true,
        ]);
        $pharmacist->roles()->syncWithoutDetaching([$pharmRole->id]);

        // ── 4. Doctors ────────────────────────────────────────────────
        Doctor::firstOrCreate(['user_id'=>$drAhmed->id], [
            'specialization'=>'طب عام', 'license_number'=>'LIC-001',
            'consultation_fee'=>200, 'is_available'=>true,
        ]);
        Doctor::firstOrCreate(['user_id'=>$drSara->id], [
            'specialization'=>'طب أطفال', 'license_number'=>'LIC-002',
            'consultation_fee'=>250, 'is_available'=>true,
        ]);

        // ── 5. Patients ───────────────────────────────────────────────
        $patientsData = [
            ['محمد عبد الله',  'male',   '1985-03-15', '01033333333', 'A+'],
            ['فاطمة أحمد',    'female', '1990-07-22', '01044444444', 'B+'],
            ['علي حسن',       'male',   '1978-11-05', '01055555555', 'O+'],
            ['نور محمود',     'female', '2000-01-30', '01066666666', 'AB+'],
            ['خالد إبراهيم',  'male',   '1965-09-18', '01077777777', 'A-'],
        ];
        foreach ($patientsData as $p) {
            Patient::firstOrCreate(['phone'=>$p[3]], [
                'name'=>$p[0], 'gender'=>$p[1], 'birth_date'=>$p[2],
                'blood_type'=>$p[4],
            ]);
        }

        // ── 6. Medicine Categories & Medicines ───────────────────────
        $cats = [
            'مضادات حيوية','مسكنات','خافضات حرارة','أدوية ضغط الدم',
            'أدوية السكري','مضادات الالتهاب','فيتامينات',
        ];
        foreach ($cats as $cat) {
            MedicineCategory::firstOrCreate(['name'=>$cat]);
        }

        $medicines = [
            [1,'أموكسيسيلين 500مجم','Amoxicillin', 'علبة',50,10,25,40, '2026-12-01','GlaxoSmithKline'],
            [1,'أزيثروميسين 250مجم','Azithromycin','علبة',30,10,45,70, '2026-08-01','Pfizer'],
            [2,'بنادول اكسترا',     'Paracetamol', 'علبة',100,20,8,15,'2027-03-01','GSK'],
            [2,'برونيل 400مجم',     'Ibuprofen',   'علبة',60,15,12,20,'2027-01-01','Noureldeen'],
            [4,'نورفاسك 5مجم',      'Amlodipine',  'علبة',25,10,55,85,'2026-11-01','Pfizer'],
            [5,'ميتفورمين 500مجم',  'Metformin',   'علبة',80,20,20,32,'2027-06-01','EIPICO'],
            [6,'ديكلوفيناك 50مجم',  'Diclofenac',  'علبة',70,15,10,18,'2027-02-01','Novartis'],
            [7,'فيتامين D3',        'Vitamin D3',  'علبة',45,10,35,60,'2027-09-01','Pharco'],
            [7,'فيتامين C 1000مجم', 'Vitamin C',   'علبة',90,20,15,25,'2027-04-01','Eva Pharma'],
        ];
        foreach ($medicines as $m) {
            Medicine::firstOrCreate(['generic_name'=>$m[1]], [
                'category_id'=>$m[0], 'name'=>$m[2], 'unit'=>$m[3],
                'quantity_in_stock'=>$m[4], 'min_stock_alert'=>$m[5],
                'purchase_price'=>$m[6], 'selling_price'=>$m[7],
                'expiry_date'=>$m[8], 'manufacturer'=>$m[9], 'is_active'=>true,
            ]);
        }

        $this->command->info('✅ تم إنشاء البيانات التجريبية!');
        $this->command->table(
            ['الدور', 'البريد الإلكتروني', 'كلمة المرور'],
            [
                ['مدير النظام',      'admin@admin.com',    'admin'],
                ['طبيب عام',         'dr.ahmed@admin.com', 'admin'],
                ['طبيبة أطفال',     'dr.sara@admin.com',  'admin'],
                ['استقبال',          'reception@admin.com','admin'],
                ['خدمة عملاء',      'cs@admin.com',       'admin'],
                ['صيدلانية',         'pharmacy@admin.com', 'admin'],
            ]
        );
    }
}
