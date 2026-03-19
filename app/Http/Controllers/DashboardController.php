<?php

namespace App\Http\Controllers;

use App\Models\BurialPermit;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Shared stats for both roles
        $stats = [
            'total'      => BurialPermit::count(),
            'this_month' => BurialPermit::whereMonth('created_at', now()->month)
                                        ->whereYear('created_at', now()->year)->count(),
            'pending'    => BurialPermit::where('status', 'pending')->count(),
            'approved'   => BurialPermit::where('status', 'approved')->count(),
            'released'   => BurialPermit::where('status', 'released')->count(),
            'expiring'   => BurialPermit::where('status', 'released')
                                        ->whereNotNull('expiry_date')
                                        ->whereDate('expiry_date', '<=', now()->addDays(30))
                                        ->whereDate('expiry_date', '>=', now())
                                        ->count(),
            'monthly'    => $this->getMonthlyData(),
        ];

        $recentPermits = BurialPermit::with('deceased')
                            ->latest()
                            ->take(5)
                            ->get();

        // Super Admin → read-only overview dashboard
        if ($user->role === 'super_admin') {
    return redirect()->route('superadmin.dashboard');
}

        // Admin → full permit processing dashboard
        return view('dashboard.admin', compact('stats', 'recentPermits'));
    }

    private function getMonthlyData(): array
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $data[] = BurialPermit::whereMonth('created_at', now()->subMonths($i)->month)
                                   ->whereYear('created_at', now()->subMonths($i)->year)
                                   ->count();
        }
        return $data;
    }
}