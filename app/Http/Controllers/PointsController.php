<?php

namespace App\Http\Controllers;

use App\Models\PointsModel;
use Illuminate\Http\Request;
use Symfony\Contracts\Service\Attribute\Required;
use Illuminate\Support\Facades\Http;

class PointsController extends Controller
{
    public function __construct()
    {
        $this->points = new PointsModel();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Peta Interaktif',
        ];
        return view('map', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        //Validate request
        $request->validate(
            [
                'nama_umkm' => 'required|unique:points,nama_umkm',
                'pemilik' => 'required',
                'jenis_usaha' => 'required',
                'telepon' => 'nullable',
                'geom_point' => 'required',
                'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:10240',

            ],
            [
                'nama_umkm.required' => 'Nama UMKM Wajib Diisi',
                'nama_umkm.unique' => 'Nama UMKM Telah Tersedia',
                'pemilik.required' => 'Nama Pemilik UMKM Wajib Diisi',
                'jenis_usaha.required' => 'Jenis Usaha UMKM Wajib Diisi',
                'geom_point.required' => 'Data Geometri Wajib Diisi',

            ]

        );

        //create images direktori if not exist
        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
        }

        //get image file
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_point." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
        } else {
            $name_image = null;
        }


        $data = [
            'geom' => $request->geom_point,
            'nama_umkm' => $request->nama_umkm,
            'pemilik' => $request->pemilik,
            'jenis_usaha' => $request->jenis_usaha,
            'telepon' => $request->telepon,
            'image' => $name_image,
            'user_id' => auth()->user()->id,
        ];


        //Create Data
        if (!$this->points->create($data)) {
            return redirect()->route('map')->with('error', 'Data Titik UMKM Gagal untuk Ditambahkan');
        }

        //kembali ke map
        return redirect()->route('map')->with('success', 'Data Titik UMKM Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = [
            'title' => 'Edit Data Titik UMKM',
            'id' => $id,
        ];
        return view('edit-point', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        //Validate request
        $request->validate(
            [
                'nama_umkm' => 'required|unique:points,nama_umkm,' . $id,
                'pemilik' => 'required',
                'jenis_usaha' => 'required',
                'telepon' => 'nullable',
                'geom_point' => 'required',
                'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:10240',
            ],
            [
                'nama_umkm.required' => 'Nama UMKM Wajib Diisi',
                'nama_umkm.unique' => 'Nama UMKM Telah Tersedia',
                'pemilik.required' => 'Nama Pemilik UMKM Wajib Diisi',
                'jenis_usaha.required' => 'Jenis Usaha UMKM Wajib Diisi',
                'geom_point.required' => 'Data Geometri Wajib Diisi',
            ]

        );

        //create images direktori if not exist
        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
        }

        //Get old Image File Name
        $old_image = $this->points->find($id)->image;

        //get image file
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_point." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);

            //Delete old Image file
            if ($old_image != null) {
                if (file_exists('./storage/images/' . $old_image)) {
                    unlink('./storage/images/' . $old_image);
                }
            }
        } else {
            $name_image = $old_image;
        }


        $data = [
            'geom' => $request->geom_point,
            'nama_umkm' => $request->nama_umkm,
            'pemilik' => $request->pemilik,
            'jenis_usaha' => $request->jenis_usaha,
            'telepon' => $request->telepon,
            'image' => $name_image,
        ];


        //Update Data
        if (!$this->points->find($id)->update($data)) {
            return redirect()->route('map')->with('error', 'Data Titik UMKM Gagal untuk di-Update');
        }

        //kembali ke map
        return redirect()->route('map')->with('success', 'Data Titik UMKM Berhasil di-Updated');
        //dd($id, $request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $imagefile = $this->points->find($id)->image;

        if (!$this->points->destroy($id)) {
            return redirect()->route('map')->with('error', 'Data Titik UMKM Gagal untuk Dihapus');
        }

        //de;ete image
        if ($imagefile != null) {
            if (file_exists('./storage/images/' . $imagefile)) {
                unlink('./storage/images/' . $imagefile);
            }
        }

        return redirect()->route('map')->with('success', 'Data Titik UMKM Berhasil Dihapus');
    }
}
