<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use App\Models\Riwayat;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class riwayatController extends Controller
{
    public function exportPDF(Request $request)
    {
        if (isset($request)) {
            $period = $request->input('timeFilter'); // Default to monthly if not specified
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
        $imageData = $request->chart_image;
        // Menghapus bagian awal dari string base64 yang tidak diperlukan untuk konversi
        $imageData = str_replace('data:image/png;base64,', '', $imageData);
        $imageData = str_replace(' ', '+', $imageData);
        $decodedImageData = base64_decode($imageData);

        // Menyimpan gambar ke dalam storage
        Storage::disk('s3')->put('chart.png', $decodedImageData);
        $dataIn = Riwayat::select(\DB::raw("DATE_FORMAT(tanggal, '$dateFormat') as tanggal"), 'id_barang', 'jenis_riwayat', \DB::raw('SUM(jumlah) as total'), 'nama_barang')
            ->where('jenis_riwayat', 'masuk') // Filter hanya untuk jenis riwayat 'masuk'
            ->groupBy(\DB::raw("DATE_FORMAT(tanggal, '$dateFormat')"), 'jenis_riwayat', 'id_barang', 'nama_barang')
            ->orderBy('tanggal', 'asc')
            ->get();
        $dataOut = Riwayat::select(\DB::raw("DATE_FORMAT(tanggal, '$dateFormat') as tanggal"), 'id_barang', 'jenis_riwayat', \DB::raw('SUM(jumlah) as total'), 'nama_barang')
            ->where('jenis_riwayat', 'keluar') // Filter hanya untuk jenis riwayat 'keluar'
            ->groupBy(\DB::raw("DATE_FORMAT(tanggal, '$dateFormat')"), 'jenis_riwayat', 'id_barang', 'nama_barang')
            ->orderBy('tanggal', 'asc')
            ->get();
        $pdf = PDF::loadView('pdf.view', compact('dataIn', 'dataOut'));
        return $pdf->download('riwayatTraficTokoman-' . now() . '.pdf');
    }

    public function index()
    {
        $years = Riwayat::selectRaw('YEAR(tanggal) as year')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();

        $months = Riwayat::selectRaw('MONTH(tanggal) as month')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

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
            ->paginate(5);

        return view('riwayat.index', [
            'riwayatTerbaru' => $riwayatTerbaru,
            'years' => $years,
            'months' => $months
        ]);
    }
    public function filterResults(Request $request)
    {
        $query = Riwayat::select([
            'riwayat.*',
            \DB::raw("DATE_FORMAT(riwayat.created_at, '%H:%i') as jam_dibuat"),
            \DB::raw("CASE 
                        WHEN riwayat.jenis_riwayat = 'keluar' THEN stok_barangs.harga_jual * riwayat.jumlah
                        WHEN riwayat.jenis_riwayat = 'masuk' THEN stok_barangs.harga_beli * riwayat.jumlah
                      END as total_harga")
        ])
            ->join('stok_barangs', 'stok_barangs.id', '=', 'riwayat.id_barang');

        // Filter jenis riwayat
        if ($request->has('jenis_riwayat') && $request->jenis_riwayat != '') {
            $query->where('riwayat.jenis_riwayat', $request->jenis_riwayat);
        }
        
        // Filter berdasarkan minggu
        if ($request->has('week') && $request->week != '') {
            if($request->week == 'today'){
                $query->whereDate('tanggal', '=', date('Y-m-d'));
            }
            else{
                $year = $request->year ?? Carbon::now()->year;
                $month = $request->month ?? Carbon::now()->month;
                $firstOfMonth = Carbon::createFromDate($year, $month, 1);
                
                $startDay = ($request->week - 1) * 7 + 1;
                $endDay = min($startDay + 6, $firstOfMonth->daysInMonth);
                
                $startDate = $firstOfMonth->copy()->addDays($startDay - 1)->startOfDay();
                $endDate = $firstOfMonth->copy()->addDays($endDay - 1)->endOfDay();
                
                $query->whereBetween('riwayat.tanggal', [ // Ganti created_at menjadi tanggal
                    $startDate->toDateTimeString(),
                    $endDate->toDateTimeString()
                ]);
            }
        }
        // Filter tahun dan bulan hanya jika week tidak diset
        else {
            if ($request->has('year') && $request->year != '') {
                $query->whereYear('riwayat.tanggal', $request->year);
            }
            if ($request->has('month') && $request->month != '') {
                $query->whereMonth('riwayat.tanggal', $request->month);
            }
        }

        $query->orderBy('riwayat.tanggal', 'desc');
        
        $results = $query->paginate(5)->appends($request->all());

        $years = Riwayat::selectRaw('YEAR(tanggal) as year')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();

        $months = Riwayat::selectRaw('MONTH(tanggal) as month')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        return view('riwayat.index', [
            'riwayatTerbaru' => $results,
            'years' => $years,
            'months' => $months
        ]);
    }

}
