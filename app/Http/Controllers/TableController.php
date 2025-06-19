<?php

namespace App\Http\Controllers;

use App\Models\PointsModel;
use Illuminate\Http\Request;
use App\Models\PolygonsModel;
use App\Models\PolylinesModel;


class TableController extends Controller
{
    public function __construct()
    {
        $this->points = new PointsModel();

    }
    public function index()
    {
        $data = [
            'title' => 'Tabel UMKM di wilayah Bandar Lampung',
            'points' => $this->points->all(),

        ];
        return view('table', $data, [
            'title' => 'Tabel Data UMKM di wilayah Bandar Lampung'
        ]);
    }
}
