<?php
namespace App\Http\Controllers\CustomerService;

use App\Http\Controllers\Controller;
use App\Models\Call;
use App\Models\Followup;

class CSDashboardController extends Controller
{
    public function index()
    {
        $todayCalls       = Call::whereDate('called_at', today())->count();
        $missedCalls      = Call::whereDate('called_at', today())->where('status', 'missed')->count();
        $pendingFollowups = Followup::where('status', 'pending')->where('assigned_to', auth()->id())->count();
        $overdueFollowups = Followup::where('status', '!=', 'completed')->where('due_date', '<', now())->count();

        $recentCalls = Call::with('patient', 'handledBy')->latest('called_at')->limit(10)->get();

        $urgentFollowups = Followup::with('patient', 'assignedTo')
            ->where('status', 'pending')->where('priority', 'urgent')
            ->orderBy('due_date')->limit(5)->get();

        return view('customer-service.dashboard.index', compact(
            'todayCalls', 'missedCalls', 'pendingFollowups',
            'overdueFollowups', 'recentCalls', 'urgentFollowups'
        ));
    }
}
