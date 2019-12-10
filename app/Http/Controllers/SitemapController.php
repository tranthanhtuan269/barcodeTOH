<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\BarCode;

class SitemapController extends Controller
{
    public function index()
    {
        $barcodes = Barcode::paginate(100);
        
        return response()->view('sitemap.index', [
            'barcodes' => $barcodes,
        ])->header('Content-Type', 'text/xml');
    }

    public function barcodes() 
    {
        $barcodes = Barcode::latest()->get();
        return response()->view('sitemap.barcodes', [
            'barcodes' => $barcodes,
        ])->header('Content-Type', 'text/xml');
    }

    public function pages() 
    {
        $barcodes = Barcode::latest()->get();
        return response()->view('sitemap.pages')->header('Content-Type', 'text/xml');
    }

}
