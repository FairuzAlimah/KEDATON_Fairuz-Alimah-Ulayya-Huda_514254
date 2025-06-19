<?php

namespace App\Http\Controllers;

use App\Models\PointsModel;
use Illuminate\Http\Request;
use App\Models\PolygonsModel;
use App\Models\PolylinesModel;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->points = new PointsModel();

    }

    public function points()
    {
        $points = $this->points->geojson_points();

        return response()->json($points);
    }

    public function point($id)
    {
        $points = $this->points->geojson_point($id);

        return response()->json($points);
    }

}
