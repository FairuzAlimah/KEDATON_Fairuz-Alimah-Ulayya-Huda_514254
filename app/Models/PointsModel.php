<?php

namespace App\Models;

use Termwind\Components\Raw;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PointsModel extends Model
{
    protected $table = 'points';
    protected $guarded = ['id'];

    public function geojson_points()
    {
        $points = $this
            ->select(DB::raw('points.id, st_asgeojson(points.geom) as geom, points.nama_umkm, points.pemilik, points.telepon, points.jenis_usaha, points.created_at, points.updated_at, points.image, points.user_id, users.name as user_created'))
            ->leftJoin('users', 'points.user_id', '=', 'users.id')
            ->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($points as $p) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'id' => $p->id,
                    'nama_umkm' => $p->nama_umkm,
                    'telepon' => $p->telepon,
                    'jenis_usaha' => $p->jenis_usaha,
                    'pemilik' => $p->pemilik,
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at,
                    'image' => $p->image,
                    'user_id' => $p->user_id,
                    'user_created'=> $p->user_created,

                ],
            ];

            array_push($geojson['features'], $feature);
        }

        return $geojson;
    }

    public function geojson_point($id)
    {
        $points = $this
            ->select(DB::raw('id, st_asgeojson(geom) as geom, nama_umkm, jenis_usaha, pemilik, telepon, created_at, updated_at, image'))
            ->where('id', $id)
            ->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($points as $p) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'id' => $p->id,
                    'nama_umkm' => $p->nama_umkm,
                    'pemilik' => $p->pemilik,
                    'jenis_usaha' => $p->jenis_usaha,
                    'telepon' => $p->telepon,
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at,
                    'image' => $p->image,
                ],
            ];

            array_push($geojson['features'], $feature);
        }

        return $geojson;
    }
}
