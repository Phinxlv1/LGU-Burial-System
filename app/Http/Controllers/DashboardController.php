<?php

namespace App\Http\Controllers;

use App\Models\BurialPermit;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {
            return redirect()->route('superadmin.dashboard');
        }

        // ── Sorting (same logic as BurialPermitController) ──
        $sort      = request()->get('sort', 'created_at');
        $direction = request()->get('direction', 'desc') === 'asc' ? 'asc' : 'desc';

        $query = BurialPermit::with('deceased');

        if ($sort === 'last_name') {
            $query->orderByRaw("(SELECT last_name FROM deceased_persons WHERE deceased_persons.id = burial_permits.deceased_id) {$direction}");
        } elseif ($sort === 'date_of_death') {
            $query->orderByRaw("(SELECT date_of_death FROM deceased_persons WHERE deceased_persons.id = burial_permits.deceased_id) {$direction}");
        } elseif (in_array($sort, ['permit_number', 'permit_type', 'created_at', 'status'])) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $recentPermits = $query->paginate(10)->withQueryString();

        $stats = [
            'total'    => BurialPermit::count(),
            'pending'  => BurialPermit::where('status', 'pending')->count(),
            'approved' => BurialPermit::where('status', 'approved')->count(),
            'released' => BurialPermit::where('status', 'released')->count(),
            'expiring' => BurialPermit::where('status', 'released')
                            ->whereNotNull('expiry_date')
                            ->whereDate('expiry_date', '<=', now()->addDays(30))
                            ->whereDate('expiry_date', '>=', now())
                            ->count(),
        ];

        return view('dashboard.admin', compact('stats', 'recentPermits'));
    }
}