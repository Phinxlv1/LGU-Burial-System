<?php

namespace App\Http\Controllers;

use App\Models\BurialPermit;
use App\Models\DeceasedPerson;

class ReportController extends Controller
{
    private function buildData(): array
    {
        $now  = now();
        $year = $now->year;

        $renewedThisWeek = BurialPermit::whereBetween('updated_at', [
            $now->copy()->startOfWeek(), $now->copy()->endOfWeek()
        ])->whereYear('created_at', '<', $year)->whereIn('status', ['approved','released'])->count();

        $renewedThisMonth = BurialPermit::whereYear('updated_at', $year)
            ->whereMonth('updated_at', $now->month)
            ->whereYear('created_at', '<', $year)
            ->whereIn('status', ['approved','released'])->count();

        $renewedThisYear = BurialPermit::whereYear('updated_at', $year)
            ->whereYear('created_at', '<', $year)
            ->whereIn('status', ['approved','released'])->count();

        $newThisWeek  = BurialPermit::whereBetween('created_at', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()])->count();
        $newThisMonth = BurialPermit::whereYear('created_at', $year)->whereMonth('created_at', $now->month)->count();
        $newThisYear  = BurialPermit::whereYear('created_at', $year)->count();

        $totalPermits    = BurialPermit::count();
        $pendingPermits  = BurialPermit::where('status', 'pending')->count();
        $approvedPermits = BurialPermit::where('status', 'approved')->count();
        $releasedPermits = BurialPermit::where('status', 'released')->count();
        $expiredPermits  = BurialPermit::where('status', 'expired')->count();

        $expiringSoon  = BurialPermit::where('status','released')->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '>=', $now)->whereDate('expiry_date', '<=', $now->copy()->addDays(30))->count();
        $expiring7Days = BurialPermit::where('status','released')->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '>=', $now)->whereDate('expiry_date', '<=', $now->copy()->addDays(7))->count();

        $totalDeceased     = DeceasedPerson::count();
        $deceasedThisMonth = DeceasedPerson::whereYear('created_at', $year)->whereMonth('created_at', $now->month)->count();
        $deceasedThisYear  = DeceasedPerson::whereYear('created_at', $year)->count();

        $monthly = BurialPermit::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', $year)->groupBy('month')
            ->pluck('total','month')->toArray();
        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) $monthlyData[$m] = $monthly[$m] ?? 0;

        $feeCounts = BurialPermit::selectRaw('permit_type, COUNT(*) as total')
            ->groupBy('permit_type')->pluck('total','permit_type')->toArray();

        $feeAmounts = ['cemented'=>1000,'niche_1st'=>8000,'niche_2nd'=>6600,'niche_3rd'=>5700,'niche_4th'=>5300,'bone_niches'=>5000];
        $feeLabels  = ['cemented'=>'Cemented','niche_1st'=>'1st Floor Niche','niche_2nd'=>'2nd Floor Niche','niche_3rd'=>'3rd Floor Niche','niche_4th'=>'4th Floor Niche','bone_niches'=>'Bone Niches'];

        $estimatedRevenue = 0;
        foreach ($feeCounts as $type => $count) $estimatedRevenue += $count * ($feeAmounts[$type] ?? 0);

        $sexBreakdown = DeceasedPerson::selectRaw('sex, COUNT(*) as total')
            ->whereNotNull('sex')->groupBy('sex')->pluck('total','sex')->toArray();

        $recentActivity = BurialPermit::with('deceased')->latest('updated_at')->limit(10)->get();
        $recentPermits  = BurialPermit::with('deceased')->latest()->limit(15)->get();

        $topMonth     = array_search(max($monthlyData ?: [0]), $monthlyData);
        $topMonthName = $topMonth ? \Carbon\Carbon::create()->month($topMonth)->format('F') : '—';

        return compact(
            'year','now',
            'renewedThisWeek','renewedThisMonth','renewedThisYear',
            'newThisWeek','newThisMonth','newThisYear',
            'totalPermits','pendingPermits','approvedPermits','releasedPermits','expiredPermits',
            'expiringSoon','expiring7Days',
            'totalDeceased','deceasedThisMonth','deceasedThisYear',
            'monthlyData','feeCounts','feeLabels','feeAmounts','estimatedRevenue',
            'sexBreakdown','recentActivity','recentPermits',
            'topMonth','topMonthName'
        );
    }

    public function index()
    {
        return view('reports.index', $this->buildData());
    }

    public function export()
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf', $this->buildData())
            ->setPaper('a4', 'portrait');
        return $pdf->download('LGU-Carmen-Report-' . now()->format('Y-m-d') . '.pdf');
    }
}