<?php
namespace App\Http\Controllers\CustomerService;

use App\Http\Controllers\Controller;
use App\Models\Call;
use App\Models\Patient;
use Illuminate\Http\Request;

class CallController extends Controller
{
    public function index(Request $request)
    {
        $calls = Call::with('patient', 'handledBy')
            ->when($request->date,   fn($q) => $q->whereDate('called_at', $request->date))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->type,   fn($q) => $q->where('type',   $request->type))
            ->latest('called_at')->paginate(25);

        $todayStats = [
            'total'    => Call::whereDate('called_at', today())->count(),
            'answered' => Call::whereDate('called_at', today())->where('status', 'answered')->count(),
            'missed'   => Call::whereDate('called_at', today())->where('status', 'missed')->count(),
        ];

        return view('customer-service.calls.index', compact('calls', 'todayStats'));
    }

    public function create()
    {
        $patients = Patient::latest()->get(['id', 'patient_code', 'name', 'phone']);
        return view('customer-service.calls.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'       => 'nullable|exists:patients,id',
            'caller_name'      => 'nullable|string|max:255',
            'caller_phone'     => 'required|string|max:20',
            'type'             => 'required|in:incoming,outgoing',
            'purpose'          => 'required|in:appointment,inquiry,complaint,followup,other',
            'status'           => 'required|in:answered,missed,callback_needed,resolved',
            'notes'            => 'nullable|string',
            'duration_seconds' => 'nullable|integer|min:0',
            'called_at'        => 'required|date',
        ]);
        $validated['handled_by'] = auth()->id();
        $call = Call::create($validated);

        return redirect()->route('cs.calls.index')->with('success', 'تم تسجيل المكالمة بنجاح');
    }

    public function show(Call $call)
    {
        $call->load('patient', 'handledBy', 'followups.assignedTo');
        return view('customer-service.calls.show', compact('call'));
    }

    public function update(Request $request, Call $call)
    {
        $request->validate(['status' => 'required|in:answered,missed,callback_needed,resolved', 'notes' => 'nullable|string']);
        $call->update($request->only('status', 'notes'));
        return back()->with('success', 'تم تحديث المكالمة');
    }
}
