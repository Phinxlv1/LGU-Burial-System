<?php

namespace App\Http\Controllers;

class CemeteryMapController extends Controller
{
    public function index()
    {
        return view('cemetery.map');
    }
}