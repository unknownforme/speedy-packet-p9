<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    /**
     * Show the tracking information for a specific package.
     */
    public function show(string $trackingNumber)
    {
        // Find the package by the tracking number
        // We use cast to integer if it was stored as one, but since it's a string 'SP-XXXX', 
        // we search by the string value.
        $package = Package::where('tracking_number', $trackingNumber)->firstOrFail();

        return view('tracking.show', compact('package'));
    }
}
