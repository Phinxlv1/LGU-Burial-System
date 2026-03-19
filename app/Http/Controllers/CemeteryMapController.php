<?php

namespace App\Http\Controllers;

use App\Models\CemeteryPlot;

class CemeteryMapController extends Controller
{
    public function index()
    {
        return view('cemetery.map');
    }
}