<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\PackageReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ReturnController extends Controller
{
    /**
     * Handle the return request.
     */
    public function store(Request $request, Package $package)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        // 1. Check if status is 'Geleverd'
        if ($package->current_status !== 'Geleverd') {
            return back()->withErrors(['error' => 'Dit pakket kan niet worden geretourneerd (status is niet Geleverd).']);
        }

        // 2. Check the 30-day window
        // Assuming we check against the last status update (delivery date)
        $lastStatus = $package->statusHistory()->latest()->first();
        if ($lastStatus && $lastStatus->created_at->addDays(30)->isBefore(now())) {
            return back()->withErrors(['error' => 'De retourtermijn van 30 dagen is verstreken.']);
        }

        // 3. Create the return request
        PackageReturn::create([
            'package_id' => $package->id,
            'requested_at' => now(),
            'reason' => $request->input('reason'),
            'status' => 'Aangevraagd',
        ]);

        // 4. Update package status and history
        $package->update(['current_status' => 'Retour aangevraagd']);
        $package->statusHistory()->create([
            'status' => 'Retour aangevraagd',
            'user_id' => Auth::id(),
            'note' => 'Retour verzoek ingediend: ' . $request->input('reason'),
            'created_at' => now(),
        ]);

        return redirect()->route('packages.show', $package)->with('success', 'Uw retourverzoek is succesvol ingediend.');
    }

    /**
     * Download the return receipt as PDF.
     */
    public function downloadReceipt(Package $package)
    {
        $return = $package->packageReturn;

        if (!$return) {
            return back()->withErrors(['error' => 'Geen retourverzoek gevonden voor dit pakket.']);
        }

        // Load a view for the PDF
        $pdf = Pdf::loadView('returns.receipt', compact('package', 'return'));

        return $pdf->download('retour_bewijs_' . $package->tracking_number . '.pdf');
    }
}
