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

        $recentPermits = BurialPermit::with('deceased')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        $stats = [
            'total' => BurialPermit::count(),
            'this_month' => BurialPermit::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)->count(),
            'pending' => BurialPermit::where('status', 'pending')->count(),
            'approved' => BurialPermit::where('status', 'approved')->count(),
            'released' => BurialPermit::where('status', 'released')->count(),
            'expired' => BurialPermit::where('status', 'expired')->count(),
            'expiring' => BurialPermit::where('status', 'released')
                ->whereNotNull('expiry_date')
                ->whereDate('expiry_date', '<=', now()->addDays(30))
                ->whereDate('expiry_date', '>=', now())
                ->count(),
            'monthly' => $this->getMonthlyData(),
        ];

        return view('dashboard.admin', compact('stats', 'recentPermits'));
    }

    private function getMonthlyData(): array
    {
        $data = [];
        for ($m = 1; $m <= 12; $m++) {
            $data[] = BurialPermit::whereMonth('created_at', $m)
                ->whereYear('created_at', now()->year)
                ->count();
        }

        return $data;
    }
}
