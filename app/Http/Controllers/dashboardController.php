<?php

namespace App\Http\Controllers;

use App\Http\Controllers\emailVerifyController as verifying;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use App\Mail\LoginNotification;
use App\Models\Laporan;
use App\Models\Riwayat;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Mail;
use Storage;


class dashboardController extends Controller
{
    public $api = null;
    public function exportPDF(Request $request)
    {
        $modifiedRequest = clone $request;

        // Set default values if not present
        $modifiedRequest->request->add([
            'period' => 'thisMonth'
        ]);
        $profitData = app('App\Http\Controllers\ProfitController')->getProfitData(
            $modifiedRequest
        );
        $totalprofit = 0;
        foreach ($profitData as $profit) {
            $totalprofit += $profit->profit;
        }
        $imageData = $request->profit_image;
        // Menghapus bagian awal dari string base64 yang tidak diperlukan untuk konversi
        $imageData = str_replace('data:image/png;base64,', '', $imageData);
        $imageData = str_replace(' ', '+', $imageData);
        $decodedImageData = base64_decode($imageData);

        // Menyimpan gambar ke dalam storage
        Storage::disk('s3')->put('profit.png', $decodedImageData);
        $pdf = PDF::loadView('pdf.profit', compact('totalprofit'));
        return $pdf->download('ProfitTraficTokoman-' . now() . '.pdf');
    }
    public function apiFetch(Request $request)
    {
        $modifiedRequest = clone $request;

        // Set default values if not present
        $modifiedRequest->request->add([
            'timeFilter' => $request->input('timeFilter'),
        ]);

        $this->api = "api";
        return $this->index($modifiedRequest);
    }
    public function index(Request $request)
    {
        if (session()->get('login') == 'true') {
            $user = Auth::user();
            $sessionId = session()->getId();
            $sessionData = \DB::table('sessions')->where('id', $sessionId)->first();
            Mail::to($user->email)->send(new LoginNotification($user, $sessionData));

        }
        $modifiedRequest = clone $request;

        // Set default values if not present
        $modifiedRequest->request->add([
            'period' => 'thisMonth'
        ]);
        $profitData = app('App\Http\Controllers\ProfitController')->getProfitData(
            $modifiedRequest
        );
        $totalProfit = 0;
        foreach ($profitData as $profit) {
            $totalProfit += $profit->profit;
        }
        if (isset($request)) {
            $period = $request->input('timeFilter');
        }
        switch ($period) {
            case 'weekly':
                $dateFormat = '%Y-%m-%u'; // Year and week number
                break;
            case 'monthly':
                $dateFormat = '%Y-%m'; // Year and month
                break;
            case 'yearly':
                $dateFormat = '%Y'; // Year only
                break;
            default:
                $dateFormat = '%Y-%m-%d'; // Default to Daily
                break;
        }

        $dataMasuk = \DB::table('riwayat')
            ->select(
                \DB::raw("DATE_FORMAT(tanggal, '$dateFormat') as tanggal"),
                \DB::raw('SUM(jumlah) as totalMasuk'),
                \DB::raw('0 as totalKeluar')  // Kolom dummy untuk totalKeluar
            )
            ->where('jenis_riwayat', 'masuk')
            ->groupBy(\DB::raw("DATE_FORMAT(tanggal, '$dateFormat')"));

        $dataKeluar = \DB::table('riwayat')
            ->select(
                \DB::raw("DATE_FORMAT(tanggal, '$dateFormat') as tanggal"),
                \DB::raw('0 as totalMasuk'),  // Kolom dummy untuk totalMasuk
                \DB::raw('SUM(jumlah) as totalKeluar')
            )
            ->where('jenis_riwayat', 'keluar')
            ->groupBy(\DB::raw("DATE_FORMAT(tanggal, '$dateFormat')"));

        $combinedData = \DB::query()
            ->select('tanggal', \DB::raw('SUM(totalMasuk) as totalMasuk'), \DB::raw('SUM(totalKeluar) as totalKeluar'))
            ->fromSub(function ($query) use ($dataMasuk, $dataKeluar) {
                $query->fromSub($dataMasuk, 'masuk')
                    ->unionAll($dataKeluar);
            }, 'combined')
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();
        // Total penjualan hari ini untuk barang keluar
        $totalToday = Laporan::whereDate('laporan.tanggal_laporan', Carbon::today())
            ->sum(\DB::raw('laporan.total'));

        // Total penjualan kemarin untuk barang keluar
        $totalYesterday = Laporan::whereDate('laporan.tanggal_laporan', Carbon::yesterday())
            ->sum(\DB::raw('laporan.total'));
        $dataThisMonth = Laporan::whereMonth('laporan.tanggal_laporan', '=', Carbon::now()->month)
            ->whereYear('laporan.tanggal_laporan', '=', Carbon::now()->year)
            ->sum(\DB::raw('laporan.total'));
        $dataLastMonth = Laporan::whereMonth('laporan.tanggal_laporan', '=', Carbon::now()->month - 1)
            ->whereYear('laporan.tanggal_laporan', '=', Carbon::now()->year)
            ->sum(\DB::raw('laporan.total'));
        $percentageThisMonth = 0;
        if ($dataLastMonth != 0) {
            $percentageThisMonth = ($dataThisMonth - $dataLastMonth) / $dataLastMonth * 100;
        }
        // Menghitung perbedaan dalam persen
        $differencePercentage = 0;
        if ($totalYesterday != 0) { // Hindari pembagian dengan nol
            $differencePercentage = (($totalToday - $totalYesterday) / $totalYesterday) * 100;
        }
        $barangPenjualan = Laporan::all()->sum('jumlah_barang');
        $riwayatTerbaru = Riwayat::select([
            'riwayat.*',
            \DB::raw("DATE_FORMAT(riwayat.created_at, '%H:%i') as jam_dibuat"),
            \DB::raw("CASE 
                        WHEN riwayat.jenis_riwayat = 'keluar' THEN stok_barangs.harga_jual * riwayat.jumlah
                        WHEN riwayat.jenis_riwayat = 'masuk' THEN stok_barangs.harga_beli * riwayat.jumlah
                      END as total_harga")
        ])
            ->join('stok_barangs', 'stok_barangs.id', '=', 'riwayat.id_barang')
            ->orderBy('riwayat.created_at', 'desc')
            ->take(5)
            ->get();
        if ($this->api == "api")
            return response()->json([
                "data" => $combinedData
            ]);
        return view(
            "dashboard",

            [
                'profit' => number_format($totalProfit, 0, ',', '.'),
                'profitData' => json_encode($profitData),
                'riwayatTerbaru' => $riwayatTerbaru,
                'totalToday' =>number_format($totalToday, 0, ',', '.'),
                'differencePercentage' => number_format($differencePercentage, 2, '.', ','),
                'dataThisMonth' => number_format($dataThisMonth, 0, ',', '.'),
                'percentageThisMonth' => number_format($percentageThisMonth, 2, '.', ','),
                'barangPenjualan' => $barangPenjualan,
                'combinedData' => json_encode($combinedData),
                'choosenPeriod' => $period,
            ]
        );
    }
}
