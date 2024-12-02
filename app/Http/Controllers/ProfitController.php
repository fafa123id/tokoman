<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Modal;
use App\Models\Laporan;
use Carbon\Carbon;


class ProfitController extends Controller
{
    public $api = null;
    public function apiFetch(Request $req)
    {
        // Clone the original request
        $modifiedRequest = clone $req;

        // Set default values if not present
        $modifiedRequest->request->add([
            'period' => $req->input('period'),
        ]);

        $this->api = "api";
        return $this->index($modifiedRequest);
    }
    public function index(Request $request)
    {
        $period = $request->input('period', 'thisMonth');

        $results = $this->filterProfitData();
        switch ($period) {
            case 'yearly':
                $finalresults = $results['yearly'];
                break;
            case 'thisMonth':
                $finalresults = $results['weekly'];
                break;
            case 'thisYear':
                $finalresults = $results['monthly'];
                break;
            default:
                $finalresults = null;
                break;
        }

        // Convert the results to JSON
        $finalresultsJson = json_encode($finalresults);

        if ($this->api == 'api') {
            return response()->json([
                'finalresults' => $finalresults
            ]);
        }
        if ($this->api == 'controller') {
            return $finalresults;
        }
        return view("hitungProfit", ['finalresults' => $finalresultsJson]);
    }


    public function filterProfitData()
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        // Query untuk Modal
        $weeklyModalQuery = DB::table('Modal')
            ->select(DB::raw("DATE_FORMAT(Tanggal, '%Y-%m-%u') as periode"), DB::raw("SUM(Total_modal) as total_modal"))
            ->whereYear('tanggal', $currentYear)
            ->whereMonth('tanggal', $currentMonth)
            ->groupBy('periode');

        // Query untuk laporan
        $weeklyLaporanQuery = DB::table('laporan')
            ->select(DB::raw("DATE_FORMAT(tanggal_laporan, '%Y-%m-%u') as periode"), DB::raw("SUM(total) as total_terjual"))
            ->whereYear('tanggal_laporan', $currentYear)
            ->whereMonth('tanggal_laporan', $currentMonth)
            ->groupBy('periode');

        // Menggabungkan hasil query
        $weeklyResult = DB::table(DB::raw("({$weeklyModalQuery->toSql()}) as modal"))
            ->mergeBindings($weeklyModalQuery)
            ->leftJoin(DB::raw("({$weeklyLaporanQuery->toSql()}) as laporan"), 'modal.periode', '=', 'laporan.periode')
            ->mergeBindings($weeklyLaporanQuery)
            ->select(
                'modal.periode',
                DB::raw("IFNULL(total_terjual, 0) - IFNULL(total_modal, 0) as profit")
            )->orderBy('modal.periode','asc')
            ->get();

        // Query untuk Modal
        $monthlyModalQuery = DB::table('Modal')
            ->select(DB::raw("DATE_FORMAT(Tanggal, '%Y-%m') as periode"), DB::raw("SUM(Total_modal) as total_modal"))
            ->whereYear('Tanggal', $currentYear)
            ->groupBy('periode');

        // Query untuk laporan
        $monthlyLaporanQuery = DB::table('laporan')
            ->select(DB::raw("DATE_FORMAT(tanggal_laporan, '%Y-%m') as periode"), DB::raw("SUM(total) as total_terjual"))
            ->whereYear('tanggal_laporan', $currentYear)
            ->groupBy('periode');

        // Menggabungkan hasil query
        $monthlyResult = DB::table(DB::raw("({$monthlyModalQuery->toSql()}) as Modal"))
            ->mergeBindings($monthlyModalQuery)
            ->leftJoin(DB::raw("({$monthlyLaporanQuery->toSql()}) as laporan"), 'Modal.periode', '=', 'laporan.periode')
            ->mergeBindings($monthlyLaporanQuery)
            ->select(
                'Modal.periode as periode',
                DB::raw("IFNULL(total_terjual, 0) - IFNULL(total_modal, 0) as profit")
            )->orderBy('Modal.periode','asc')
            ->get();

        // Query untuk Modal
        $yearlyModalQuery = DB::table('Modal')
            ->select(DB::raw("YEAR(Tanggal) as periode"), DB::raw("SUM(Total_modal) as total_modal"))
            ->groupBy('periode');

        // Query untuk laporan
        $yearlyLaporanQuery = DB::table('laporan')
            ->select(DB::raw("YEAR(tanggal_laporan) as periode"), DB::raw("SUM(total) as total_terjual"))
            ->groupBy('periode');

        // Menggabungkan hasil query
        $yearlyResult = DB::table(DB::raw("({$yearlyModalQuery->toSql()}) as Modal"))
            ->mergeBindings($yearlyModalQuery)
            ->leftJoin(DB::raw("({$yearlyLaporanQuery->toSql()}) as laporan"), 'Modal.periode', '=', 'laporan.periode')
            ->mergeBindings($yearlyLaporanQuery)
            ->select(
                'Modal.periode as periode',
                DB::raw("IFNULL(total_terjual, 0) - IFNULL(total_modal, 0) as profit")
            )->orderBy('Modal.periode','asc')
            ->get();
        $results = [
            'weekly' => $weeklyResult,
            'monthly' => $monthlyResult,
            'yearly' => $yearlyResult,
        ];

        // Menampilkan hasil
        return $results;
    }
    public function getProfitData(Request $req)
    {
        // Clone the original request
        $modifiedRequest = clone $req;

        // Set default values if not present
        $modifiedRequest->request->add([
            'period' => $req->input('period'),
        ]);

        $this->api = "controller";
        return $this->index($modifiedRequest);
    }
}
