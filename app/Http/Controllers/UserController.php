<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\BurialPermit;
use App\Models\DeceasedPerson;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        $year = now()->year;

        $totalPermits      = BurialPermit::count();
        $pendingPermits    = BurialPermit::where('status', 'pending')->count();
        $approvedPermits   = BurialPermit::where('status', 'approved')->count();
        $releasedPermits   = BurialPermit::where('status', 'released')->count();
        $expiredPermits    = BurialPermit::where('status', 'expired')->count();
        $permitsThisMonth  = BurialPermit::whereMonth('created_at', now()->month)
                                          ->whereYear('created_at', $year)->count();
        $totalDeceased     = DeceasedPerson::count();
        $deceasedThisMonth = DeceasedPerson::whereMonth('created_at', now()->month)
                                            ->whereYear('created_at', $year)->count();

        // Monthly bar chart (Jan–Dec)
        $monthly = BurialPermit::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[] = $monthly[$m] ?? 0;
        }

        // Fee type breakdown
        $feeCounts = BurialPermit::selectRaw('permit_type, COUNT(*) as total')
            ->groupBy('permit_type')
            ->pluck('total', 'permit_type')
            ->toArray();

        $feeTypeData = [
            $feeCounts['cemented']    ?? 0,
            $feeCounts['niche_1st']   ?? 0,
            $feeCounts['niche_2nd']   ?? 0,
            $feeCounts['niche_3rd']   ?? 0,
            $feeCounts['niche_4th']   ?? 0,
            $feeCounts['bone_niches'] ?? 0,
            collect($feeCounts)->except(['cemented','niche_1st','niche_2nd','niche_3rd','niche_4th','bone_niches'])->sum(),
        ];

        $recentPermits = BurialPermit::with('deceased')->latest()->limit(8)->get();

        // Recent activity for the dashboard preview (last 8)
        $recentActivity = ActivityLog::with('user')->latest()->limit(8)->get();

        return view('superadmin.dashboard', compact(
            'totalPermits', 'pendingPermits', 'approvedPermits',
            'releasedPermits', 'expiredPermits',
            'permitsThisMonth', 'totalDeceased', 'deceasedThisMonth',
            'monthlyData', 'feeTypeData', 'recentPermits', 'recentActivity'
        ));
    }
}