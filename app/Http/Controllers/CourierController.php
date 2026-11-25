<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Services\PackageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourierController extends Controller
{
    protected $packageService;

    public function __construct(PackageService $packageService)
    {
        $this->packageService = $packageService;
    }

    /**
     * Display a listing of the courier's assigned packages.
     */
    public function index()
    {
        // For simulation, we show packages that are not yet delivered or failed
        $packages = Package::whereNotIn('current_status', ['Geleverd', 'Mislukte levering'])->get();

        return view('courier.index', compact('packages'));
    }

    /**
     * Show the specific details of a package for delivery.
     */
    public function show(Package $package)
    {
        return view('courier.show', compact('package'));
    }

    /**
     * Process the delivery outcome.
     */
    public function update(Request $request, Package $package)
    {
        $request->validate([
            'outcome' => 'required|string',
            'reason' => 'nullable|string|max:255',
        ]);

        $this->packageService->recordDeliveryOutcome(
            $package,
            $request->input('outcome'),
            $request->input('reason')
        );

        return redirect()->route('courier.index')->with('success', 'Status bijgewerkt.');
    }
}
