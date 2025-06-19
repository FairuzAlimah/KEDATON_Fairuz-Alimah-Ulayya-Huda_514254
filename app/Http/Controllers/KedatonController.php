<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KedatonController extends Controller
{
    public function index()
    {
        // Kirim data title ke view (optional)
        return view('kedaton', [
            'title' => 'Tentang Aplikasi & UMKM'
        ]);
    }
}
