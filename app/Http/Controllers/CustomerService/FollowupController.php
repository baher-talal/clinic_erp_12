<?php
namespace App\Http\Controllers\CustomerService;

use App\Http\Controllers\Controller;
use App\Models\Followup;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;

class FollowupController extends Controller
{
    public function index(Request $request)
    {
        $followups = Followup::with('patient', 'assignedTo', 'createdBy')
            ->when($request->status,   fn($q) => $q->where('status', $request->status))
            ->when($request->priority, fn($q) => $q->where('priority', $request->priority))
            ->when($request->mine,     fn($q) => $q->where('assigned_to', auth()->id()))
            ->when($request->overdue,  fn($q) => $q->where('due_date', '<', now())->where('status', '!=', 'completed'))
            ->orderByRaw("FIELD(priority, 'urgent','high','medium','low')")
            ->orderBy('due_date')
            ->paginate(20);

        $stats = [
            'pending'   => Followup::where('status', 'pending')->count(),
            'overdue'   => Followup::where('status', '!=', 'completed')->where('due_date', '<', now())->count(),
            'today_due' => Followup::whereDate('due_date', today())->where('status', '!=', 'completed')->count(),
            'mine'      => Followup::where('assigned_to', auth()->id())->where('status', 'pending')->count(),
        ];

        return view('customer-service.followups.index', compact('followups', 'stats'));
    }

    public function create(Request $request)
    {
        $patients = Patient::latest()->get(['id', 'patient_code', 'name', 'phone']);
        $agents   = User::whereHas('roles', fn($q) => $q->whereIn('name', ['customer_service', 'admin']))->get(['id', 'name']);
        $callId   = $request->call_id;
        return view('customer-service.followups.create', compact('patients', 'agents', 'callId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'  => 'nullable|exists:patients,id',
            'call_id'     => 'nullable|exists:calls,id',
            'assigned_to' => 'required|exists:users,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'type'        => 'required|in:appointment_reminder,post_treatment,complaint,lab_results,other',
            'priority'    => 'required|in:low,medium,high,urgent',
            'due_date'    => 'required|date|after:now',
        ]);
        $validated['created_by'] = auth()->id();
        Followup::create($validated);
        return redirect()->route('cs.followups.index')->with('success', 'تم إنشاء المتابعة بنجاح');
    }

    public function show(Followup $followup)
    {
        $followup->load('patient', 'assignedTo', 'createdBy', 'call');
        return view('customer-service.followups.show', compact('followup'));
    }

    public function complete(Request $request, Followup $followup)
    {
        $request->validate(['resolution_notes' => 'nullable|string']);
        $followup->update([
            'status'           => 'completed',
            'resolution_notes' => $request->resolution_notes,
            'completed_at'     => now(),
        ]);
        return back()->with('success', 'تم إغلاق المتابعة بنجاح');
    }

    public function updateStatus(Request $request, Followup $followup)
    {
        $request->validate(['status' => 'required|in:pending,in_progress,completed,cancelled']);
        $followup->update(['status' => $request->status]);
        return back()->with('success', 'تم تحديث الحالة');
    }
}
