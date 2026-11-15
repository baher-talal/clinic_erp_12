<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => ['required', Password::min(8)],
            'phone'       => 'nullable|string|max:20',
            'job_title'   => 'nullable|string|max:100',
            'department'  => 'nullable|string|max:100',
            'joined_date' => 'nullable|date',
            'roles'       => 'required|array|min:1',
            'roles.*'     => 'exists:roles,id',
            // Doctor fields
            'specialization'   => 'nullable|string|max:100',
            'license_number'   => 'nullable|string|unique:doctors,license_number',
            'consultation_fee' => 'nullable|numeric|min:0',
        ]);

        $user = User::create([
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'password'    => Hash::make($validated['password']),
            'phone'       => $validated['phone'] ?? null,
            'job_title'   => $validated['job_title'] ?? null,
            'department'  => $validated['department'] ?? null,
            'joined_date' => $validated['joined_date'] ?? null,
            'is_active'   => true,
        ]);

        $user->roles()->sync($validated['roles']);

        // إذا كان دكتور، أنشئ سجل Doctor تلقائياً
        $doctorRole = Role::where('name', 'doctor')->first();
        if ($doctorRole && in_array($doctorRole->id, $validated['roles']) && !empty($validated['specialization'])) {
            Doctor::create([
                'user_id'          => $user->id,
                'specialization'   => $validated['specialization'],
                'license_number'   => $validated['license_number'] ?? 'LIC-' . $user->id,
                'consultation_fee' => $validated['consultation_fee'] ?? 0,
                'is_available'     => true,
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    public function edit(User $user)
    {
        $roles       = Role::all();
        $userRoleIds = $user->roles->pluck('id')->toArray();
        return view('admin.users.edit', compact('user', 'roles', 'userRoleIds'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'phone'      => 'nullable|string|max:20',
            'job_title'  => 'nullable|string|max:100',
            'department' => 'nullable|string|max:100',
            'is_active'  => 'boolean',
            'roles'      => 'required|array|min:1',
            'roles.*'    => 'exists:roles,id',
        ]);

        $user->update($validated);
        $user->roles()->sync($validated['roles']);

        if ($request->filled('password')) {
            $request->validate(['password' => Password::min(8)]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'تم تحديث المستخدم بنجاح');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'لا يمكنك حذف حسابك الخاص');
        }
        $user->delete();
        return back()->with('success', 'تم حذف المستخدم');
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', $user->is_active ? 'تم تفعيل المستخدم' : 'تم تعطيل المستخدم');
    }
}
