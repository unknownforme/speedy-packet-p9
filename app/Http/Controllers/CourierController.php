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

        // Restriction: Only scan if the package is currently in the distribution center
        if ($package->current_status !== 'In distributiecentrum') {
            return response()->json([
                'error' => 'Dit pakket bevindt zich niet in het distributiecentrum en kan niet door een koerier worden gescand.'
            ], 403);
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

    /**
     * Update the delivery outcome.
     */
    public function update(Request $request, Package $package)
    {
        $request->validate([
            'outcome' => 'required|string',
            'reason' => 'nullable|string|max:500',
            'extra_info' => 'nullable|string|max:500',
        ]);

        $outcome = $request->outcome;
        $reason = $request->reason;
        $extra_info = $request->extra_info;

        $newStatus = '';
        $historyStatus = '';
        $note = '';

        if ($outcome === 'delivered_house') {
            $newStatus = 'Delivered (at house)';
            $historyStatus = 'Delivered (at house)';
            $note = 'Delivered directly to the recipient.';
        } elseif ($outcome === 'delivered_neighbour') {
            $newStatus = 'Delivered (to neighbour)';
            $historyStatus = 'Delivered (to neighbour)';
            $note = 'Delivered to a neighbour. Info: ' . ($extra_info ?? 'N/A');
        } elseif ($outcome === 'delivered_service_point') {
            $newStatus = 'Delivered (at service point)';
            $historyStatus = 'Delivered (at service point)';
            $note = 'Delivered to service point: ' . ($extra_info ?? 'N/A');
        } elseif ($outcome === 'not_delivered') {
            $newStatus = 'In distributiecentrum';
            $historyStatus = 'Not Delivered';
            $note = $reason ?? 'Reason not provided.';
        }

        if (!$newStatus) {
            return back()->with('error', 'Ongeldige leveringsmethode.');
        }

        // Prevent duplicate status entries
        if ($package->current_status === $newStatus) {
            return back()->with('info', 'Dit is de huidige status al.');
        }

        $package->update(['current_status' => $newStatus]);

        $package->statusHistory()->create([
            'status' => $historyStatus,
            'user_id' => Auth::id(),
            'notes' => $note,
            'created_at' => now(),
        ]);

        return back()->with('success', 'Levering succesvol bijgewerkt.');
    }
}
