<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PointsModel;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    public function index()
    {
        $total_umkm = PointsModel::count();
        $umkm_aktif = $total_umkm;
        $kategori_umkm = PointsModel::distinct('jenis_usaha')->count();

        $chart_data = PointsModel::select('jenis_usaha', DB::raw('count(*) as total'))
                        ->groupBy('jenis_usaha')
                        ->get();

        $labels = $chart_data->pluck('jenis_usaha');
        $data = $chart_data->pluck('total');

        $viewData = [
            'title' => 'Halaman Utama',
            'total_umkm' => $total_umkm,

            'kategori_umkm' => $kategori_umkm,
            'labels' => $labels,
            'data' => $data
        ];

        return view('home', $viewData);
    }
}
