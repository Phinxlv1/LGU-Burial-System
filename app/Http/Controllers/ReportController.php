<?php

namespace App\Http\Controllers;

use App\Models\BurialPermit;
use App\Models\DeceasedPerson;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        $year  = now()->year;
        $month = now()->month;

        $totalPermits    = BurialPermit::count();
        $activePermits   = BurialPermit::where('status', 'active')->count();
        $expiringPermits = BurialPermit::where('status', 'expiring')->count();
        $expiredPermits  = BurialPermit::where('status', 'expired')->count();

        $newPermits       = BurialPermit::whereYear('created_at', $year)->count();
        $permitsThisMonth = BurialPermit::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
        $permitsThisWeek  = BurialPermit::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();

        $renewalsThisYear  = BurialPermit::whereYear('updated_at', $year)->whereYear('created_at', '<', $year)->where('status', 'active')->count();
        $renewalsThisMonth = BurialPermit::whereYear('updated_at', $year)->whereMonth('updated_at', $month)->whereYear('created_at', '<', $year)->where('status', 'active')->count();
        $renewalsThisWeek  = BurialPermit::whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()])->whereYear('created_at', '<', $year)->where('status', 'active')->count();

        $urgentExpiring = BurialPermit::where('status', 'expiring')->whereNotNull('expiry_date')->whereDate('expiry_date', '>=', now())->whereDate('expiry_date', '<=', now()->addDays(7))->count();
        $expiringSoon   = BurialPermit::where('status', 'expiring')->whereNotNull('expiry_date')->whereDate('expiry_date', '>=', now())->whereDate('expiry_date', '<=', now()->addDays(30))->count();

        $totalDeceased     = DeceasedPerson::count();
        $deceasedThisYear  = DeceasedPerson::whereYear('created_at', $year)->count();
        $deceasedThisMonth = DeceasedPerson::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();

        $monthly = BurialPermit::selectRaw("CAST(strftime('%m', created_at) AS INTEGER) as month, COUNT(*) as total")
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyData = [];
        $monthNames  = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[$m - 1] = $monthly[$m] ?? 0;
        }
        $maxVal       = max(array_merge($monthlyData, [1]));
        $peakIdx      = array_search($maxVal, $monthlyData);
        $busiestMonth = $monthNames[$peakIdx] ?? '—';
        $busiestCount = $maxVal;

        $feeCounts = BurialPermit::selectRaw('permit_type, COUNT(*) as total')
            ->groupBy('permit_type')
            ->pluck('total', 'permit_type')
            ->toArray();

        $feeAmounts = ['cemented'=>1000,'niche_1st'=>8000,'niche_2nd'=>6600,'niche_3rd'=>5700,'niche_4th'=>5300,'bone_niches'=>5000];
        $estimatedRevenue = 0;
        foreach ($feeCounts as $type => $count) {
            $estimatedRevenue += $count * ($feeAmounts[$type] ?? 0);
        }

        $recentPermits  = BurialPermit::with('deceased')->latest()->limit(10)->get();
        $renewedPermits = $renewalsThisYear;

        return view('reports.index', compact(
            'totalPermits', 'activePermits', 'expiringPermits', 'expiredPermits',
            'newPermits', 'permitsThisMonth', 'permitsThisWeek',
            'renewedPermits', 'renewalsThisYear', 'renewalsThisMonth', 'renewalsThisWeek',
            'urgentExpiring', 'expiringSoon',
            'totalDeceased', 'deceasedThisYear', 'deceasedThisMonth',
            'monthlyData', 'busiestMonth', 'busiestCount',
            'feeCounts', 'estimatedRevenue',
            'recentPermits'
        ));
    }

    public function superAdminIndex()
    {
        $year  = now()->year;
        $month = now()->month;

        $totalPermits    = BurialPermit::count();
        $activePermits   = BurialPermit::where('status', 'active')->count();
        $expiringPermits = BurialPermit::where('status', 'expiring')->count();
        $expiredPermits  = BurialPermit::where('status', 'expired')->count();

        $newPermits       = BurialPermit::whereYear('created_at', $year)->count();
        $permitsThisMonth = BurialPermit::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
        $permitsThisWeek  = BurialPermit::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();

        $renewedPermits    = BurialPermit::whereYear('updated_at', $year)->whereYear('created_at', '<', $year)->where('status', 'active')->count();
        $renewalsThisYear  = $renewedPermits;
        $renewalsThisMonth = BurialPermit::whereYear('updated_at', $year)->whereMonth('updated_at', $month)->whereYear('created_at', '<', $year)->where('status', 'active')->count();
        $renewalsThisWeek  = BurialPermit::whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()])->whereYear('created_at', '<', $year)->where('status', 'active')->count();

        $urgentExpiring = BurialPermit::where('status', 'expiring')->whereNotNull('expiry_date')->whereDate('expiry_date', '>=', now())->whereDate('expiry_date', '<=', now()->addDays(7))->count();
        $expiringSoon   = BurialPermit::where('status', 'expiring')->whereNotNull('expiry_date')->whereDate('expiry_date', '>=', now())->whereDate('expiry_date', '<=', now()->addDays(30))->count();

        $totalDeceased     = DeceasedPerson::count();
        $deceasedThisYear  = DeceasedPerson::whereYear('created_at', $year)->count();
        $deceasedThisMonth = DeceasedPerson::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();

        $monthly = BurialPermit::selectRaw("CAST(strftime('%m', created_at) AS INTEGER) as month, COUNT(*) as total")
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyData = [];
        $monthNames  = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[$m - 1] = $monthly[$m] ?? 0;
        }
        $maxVal       = max(array_merge($monthlyData, [1]));
        $peakIdx      = array_search($maxVal, $monthlyData);
        $busiestMonth = $monthNames[$peakIdx] ?? '—';
        $busiestCount = $maxVal;

        $feeCounts = BurialPermit::selectRaw('permit_type, COUNT(*) as total')
            ->groupBy('permit_type')
            ->pluck('total', 'permit_type')
            ->toArray();

        $feeAmounts = ['cemented'=>1000,'niche_1st'=>8000,'niche_2nd'=>6600,'niche_3rd'=>5700,'niche_4th'=>5300,'bone_niches'=>5000];
        $estimatedRevenue = 0;
        foreach ($feeCounts as $type => $count) {
            $estimatedRevenue += $count * ($feeAmounts[$type] ?? 0);
        }

        $recentPermits = BurialPermit::with('deceased')->latest()->limit(10)->get();

        return view('superadmin.reports', compact(
            'totalPermits', 'activePermits', 'expiringPermits', 'expiredPermits',
            'newPermits', 'permitsThisMonth', 'permitsThisWeek',
            'renewedPermits', 'renewalsThisYear', 'renewalsThisMonth', 'renewalsThisWeek',
            'urgentExpiring', 'expiringSoon',
            'totalDeceased', 'deceasedThisYear', 'deceasedThisMonth',
            'monthlyData', 'busiestMonth', 'busiestCount',
            'feeCounts', 'estimatedRevenue',
            'recentPermits'
        ));
    }

    public function export()
    {
        $year = now()->year;

        $totalPermits    = BurialPermit::count();
        $activePermits   = BurialPermit::where('status', 'active')->count();
        $expiringPermits = BurialPermit::where('status', 'expiring')->count();
        $expiredPermits  = BurialPermit::where('status', 'expired')->count();
        $totalDeceased   = DeceasedPerson::count();

        $newPermits = BurialPermit::whereYear('created_at', $year)->count();

        $renewedPermits = BurialPermit::whereYear('updated_at', $year)
            ->whereYear('created_at', '<', $year)
            ->where('status', 'active')
            ->count();

        $monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];

        $monthly = BurialPermit::selectRaw("CAST(strftime('%m', created_at) AS INTEGER) as month, COUNT(*) as total")
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // 0-indexed array (Jan=0 … Dec=11) to match the blade foreach
        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[$m - 1] = $monthly[$m] ?? 0;
        }

        $maxVal       = max(array_merge($monthlyData, [1]));
        $peakIdx      = array_search($maxVal, $monthlyData);
        $busiestMonth = $monthNames[$peakIdx] ?? '—';
        $busiestCount = $maxVal;

        $feeCounts = BurialPermit::selectRaw('permit_type, COUNT(*) as total')
            ->groupBy('permit_type')
            ->pluck('total', 'permit_type')
            ->toArray();

        $recentPermits = BurialPermit::with('deceased')->latest()->limit(15)->get();

        $pdf = Pdf::loadView('superadmin.pdf_export', compact(
            'totalPermits', 'activePermits', 'expiringPermits',
            'expiredPermits', 'totalDeceased',
            'newPermits', 'renewedPermits',
            'monthlyData', 'busiestMonth', 'busiestCount',
            'feeCounts', 'recentPermits', 'year'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('LGU-Carmen-Burial-Report-'.now()->format('Y-m-d').'.pdf');
    }
}