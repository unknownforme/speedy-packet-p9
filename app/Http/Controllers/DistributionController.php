<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Package;
use App\Services\PackageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DistributionController extends Controller
{
    protected $packageService;

    public function __construct(PackageService $packageService)
    {
        $this->packageService = $packageService;
    }

    /**
     * Display the distribution center dashboard.
     */
    public function index()
    {
        // Only users with the correct role can access the dashboard
        if (!Auth::user()->hasRole('Distributiemedewerker') && !Auth::user()->hasRole('Admin')) {
            abort(403, 'Unauthorized access to distribution dashboard.');
        }

        // Show packages that are currently in the DC workflow
        $packages = Package::whereIn('current_status', ['Registered', 'At Distribution Center', 'Sorting', 'In distributiecentrum'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('distribution.index', compact('packages'));
    }

    /**
     * "Scan" a package by tracking number and automatically update its status.
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

        // Automatic update to "In distributiecentrum"
        $newStatus = 'In distributiecentrum';
        
        // Update the package
        $package->update([
            'current_status' => $newStatus
        ]);

        // Record the history
        $package->statusHistory()->create([
            'status' => $newStatus,
            'user_id' => Auth::id(),
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'package' => [
                'id' => $package->id,
                'tracking_number' => $package->tracking_number,
                'current_status' => $newStatus,
                'destination_province' => $package->destination_province,
                'weight_grams' => $package->weight_grams,
                'receiver_email' => $package->receiver_email,
            ]
        ]);
    }

    /**
     * Update package status or details during distribution.
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'new_status' => 'required|string',
            'destination_province' => 'nullable|string',
        ]);

        $package = Package::findOrFail($request->package_id);

        // Prevent illegal transitions (simplified logic)
        // In a real app, we would use a State Machine.
        
        $oldStatus = $package->current_status;
        $newStatus = $request->new_status;

        // Update the package
        $updateData = [
            'current_status' => $newStatus,
        ];

        if ($request->has('destination_province')) {
            $updateData['destination_province'] = $request->destination_province;
        }

        $package->fill($updateData);
        $package->save();

        // Record the history
        $package->statusHistory()->create([
            'status' => $newStatus,
            'user_id' => Auth::id(),
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Package status updated from {$oldStatus} to {$newStatus}.",
            'package' => $package
        ]);
    }
}
