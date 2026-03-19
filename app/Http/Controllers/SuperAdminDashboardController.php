<?php

namespace App\Http\Controllers;

use App\Models\BurialPermit;
use App\Models\DeceasedPerson;
use Illuminate\Support\Facades\DB;

class SuperAdminDashboardController extends Controller
{

    public function export()
{
    $year = now()->year;

    $totalPermits    = BurialPermit::count();
    $pendingPermits  = BurialPermit::where('status','pending')->count();
    $approvedPermits = BurialPermit::where('status','approved')->count();
    $releasedPermits = BurialPermit::where('status','released')->count();
    $expiredPermits  = BurialPermit::where('status','expired')->count();
    $totalDeceased   = DeceasedPerson::count();

    // New permits = created this year
    $newPermits = BurialPermit::whereYear('created_at', $year)->count();

    // Renewed = expiry_date was updated this year but permit is older than this year
    $renewedPermits = BurialPermit::whereYear('updated_at', $year)
        ->whereYear('created_at', '<', $year)
        ->where('status', 'released')
        ->count();

    $monthly = BurialPermit::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
        ->whereYear('created_at', $year)->groupBy('month')
        ->pluck('total','month')->toArray();
    $monthlyData = [];
    for($m=1;$m<=12;$m++) $monthlyData[$m] = $monthly[$m] ?? 0;

    $feeCounts = BurialPermit::selectRaw('permit_type, COUNT(*) as total')
        ->groupBy('permit_type')->pluck('total','permit_type')->toArray();

    $recentPermits = BurialPermit::with('deceased')->latest()->limit(15)->get();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('superadmin.report', compact(
        'totalPermits','pendingPermits','approvedPermits',
        'releasedPermits','expiredPermits','totalDeceased',
        'newPermits','renewedPermits',
        'monthlyData','feeCounts','recentPermits','year'
    ))->setPaper('a4','portrait');

    return $pdf->download('LGU-Carmen-Burial-Report-' . now()->format('Y-m-d') . '.pdf');
}

    public function index()
    {
        $year = now()->year;

        // ── Stat Cards ──
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

        // ── Monthly Bar Chart (Jan–Dec) ──
        $monthly = BurialPermit::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[] = $monthly[$m] ?? 0;
        }

        // ── Fee Type Breakdown ──
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
            // lump everything else (old 'new','transfer',etc.) into Other
            collect($feeCounts)->except(['cemented','niche_1st','niche_2nd','niche_3rd','niche_4th','bone_niches'])->sum(),
        ];

        // ── Recent Permits ──
        $recentPermits = BurialPermit::with('deceased')->latest()->limit(8)->get();

        return view('superadmin.dashboard', compact(
            'totalPermits', 'pendingPermits', 'approvedPermits',
            'releasedPermits', 'expiredPermits',
            'permitsThisMonth', 'totalDeceased', 'deceasedThisMonth',
            'monthlyData', 'feeTypeData', 'recentPermits'
        ));
    }
}
