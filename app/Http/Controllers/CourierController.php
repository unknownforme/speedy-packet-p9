<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\User;
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
     * Display the courier's dashboard (their current assignments).
     */
    public function index()
    {
        // Show packages assigned to the current courier
        $packages = Package::where('assigned_to_user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('courier.index', compact('packages'));
    }

    /**
     * Show details for a specific package.
     */
    public function show(Package $package)
    {
        // Ensure the courier is assigned to this package
        if ($package->assigned_to_user_id !== Auth::id()) {
            abort(403, 'Je bent niet verantwoordelijk voor dit pakket.');
        }

        return view('courier.show', compact('package'));
    }

    /**
     * Process a scanned package.
     */
    public function scan(Request $request)
    {
        $request->validate([
            'tracking_number' => 'required|string',
        ]);

        $package = Package::where('tracking_number', $request->tracking_number)->first();

        if (!$package) {
            return response()->json(['error' => 'Pakket niet gevonden.'], 404);
        }

        // Assign to the courier
        $package->update([
            'assigned_to_user_id' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'package' => [
                'id' => $package->id,
                'tracking_number' => $package->tracking_number,
                'receiver_street' => $package->receiver_street,
                'receiver_house_number' => $package->receiver_house_number,
                'receiver_city' => $package->receiver_city,
            ]
        ]);
    }
}
