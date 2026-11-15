<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\{DashboardController,PatientController,AppointmentController,PharmacyController,TreasuryController,UserController,RoleController,DoctorManageController,ReportController};
use App\Http\Controllers\Doctor\{MedicalRecordController,DoctorDashboardController};
use App\Http\Controllers\CustomerService\{CSDashboardController,CallController,FollowupController};

// Auth
Route::get('/', fn() => redirect()->route('login'));
Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => view('auth.login'))->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth'])->group(function () {

    // ─── Admin & Reception ───────────────────────────────────────────
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Patients
    Route::resource('patients', PatientController::class);
    Route::get('/patients/search/ajax', [PatientController::class, 'search'])->name('patients.search');

    // Appointments
    Route::resource('appointments', AppointmentController::class);
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status');
    Route::post('/appointments/{appointment}/pay',    [AppointmentController::class, 'markPaid'])->name('appointments.pay');
    Route::get('/calendar',                           [AppointmentController::class, 'calendar'])->name('appointments.calendar');

    // Medical Records
    Route::get( '/appointments/{appointment}/record/create', [MedicalRecordController::class, 'create'])->name('records.create');
    Route::post('/appointments/{appointment}/record',        [MedicalRecordController::class, 'store'])->name('records.store');
    Route::get( '/records/{record}',                         [MedicalRecordController::class, 'show'])->name('records.show');

    // Pharmacy
    Route::prefix('pharmacy')->name('pharmacy.')->group(function () {
        Route::get('/',                                               [PharmacyController::class, 'index'])->name('index');
        Route::post('/medicines',                                     [PharmacyController::class, 'store'])->name('medicines.store');
        Route::post('/prescriptions/{prescription}/dispense',        [PharmacyController::class, 'dispensePrescription'])->name('dispense');
        Route::get('/low-stock',                                      [PharmacyController::class, 'lowStock'])->name('low-stock');
    });

    // Treasury
    Route::prefix('treasury')->name('treasury.')->group(function () {
        Route::get('/',        [TreasuryController::class, 'index'])->name('index');
        Route::post('/',       [TreasuryController::class, 'store'])->name('store');
        Route::get('/report',  [TreasuryController::class, 'report'])->name('report');
    });

    // ─── Admin Only ──────────────────────────────────────────────────
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {

        // Users
        Route::resource('users', UserController::class);
        Route::patch('/users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle');

        // Roles & Permissions
        Route::get('/roles',                      [RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/{role}/edit',          [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}',               [RoleController::class, 'update'])->name('roles.update');

        // Doctors management
        Route::get('/doctors',                    [DoctorManageController::class, 'index'])->name('doctors.index');
        Route::get('/doctors/{doctor}',           [DoctorManageController::class, 'show'])->name('doctors.show');
        Route::put('/doctors/{doctor}',           [DoctorManageController::class, 'update'])->name('doctors.update');

        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/',            [ReportController::class, 'index'])->name('index');
            Route::get('/appointments',[ReportController::class, 'appointments'])->name('appointments');
            Route::get('/patients',    [ReportController::class, 'patients'])->name('patients');
            Route::get('/financial',   [ReportController::class, 'financial'])->name('financial');
        });
    });

    // ─── Doctor Interface ────────────────────────────────────────────
    Route::prefix('doctor')->name('doctor.')->middleware('role:doctor')->group(function () {
        Route::get('/dashboard',          [DoctorDashboardController::class, 'index'])->name('dashboard');
        Route::get('/appointments',       [DoctorDashboardController::class, 'appointments'])->name('appointments');
        Route::get('/patients',           [DoctorDashboardController::class, 'patients'])->name('patients');
        Route::get('/patients/{patient}', [DoctorDashboardController::class, 'patientProfile'])->name('patients.show');
    });

    // ─── Customer Service Interface ──────────────────────────────────
    Route::prefix('cs')->name('cs.')->middleware('role:customer_service,admin')->group(function () {
        Route::get('/dashboard', [CSDashboardController::class, 'index'])->name('dashboard');

        // Calls
        Route::get('/calls',           [CallController::class, 'index'])->name('calls.index');
        Route::get('/calls/create',    [CallController::class, 'create'])->name('calls.create');
        Route::post('/calls',          [CallController::class, 'store'])->name('calls.store');
        Route::get('/calls/{call}',    [CallController::class, 'show'])->name('calls.show');
        Route::put('/calls/{call}',    [CallController::class, 'update'])->name('calls.update');

        // Followups
        Route::get('/followups',                      [FollowupController::class, 'index'])->name('followups.index');
        Route::get('/followups/create',               [FollowupController::class, 'create'])->name('followups.create');
        Route::post('/followups',                     [FollowupController::class, 'store'])->name('followups.store');
        Route::get('/followups/{followup}',           [FollowupController::class, 'show'])->name('followups.show');
        Route::patch('/followups/{followup}/complete',[FollowupController::class, 'complete'])->name('followups.complete');
        Route::patch('/followups/{followup}/status',  [FollowupController::class, 'updateStatus'])->name('followups.status');
    });
});
