<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\User;
use App\Services\PackageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{
    protected $packageService;

    public function __construct(PackageService $packageService)
    {
        $this->packageService = $packageService;
    }

    /**
     * Display a listing of the user's packages.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('Admin')) {
            $packages = Package::orderBy('created_at', 'desc')->get();
        } else {
            $packages = Package::where('receiver_id', $user->id)
                ->orWhere('company_id', $user->company_id ?? 0)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new package.
     */
    public function create()
    {
        $methods = \App\Models\ShippingMethod::all();
        $provinces = ['Groningen', 'Friesland', 'Drenthe', 'Overijssel', 'Flevoland', 'Gelderland', 'Utrecht', 'Noord-Holland', 'Zuid-Holland', 'Zeeland', 'Noord-Brabant', 'Limburg'];

        return view('packages.create', compact('methods', 'provinces'));
    }

    /**
     * Store a newly created package in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'shipping_method_id' => 'required|exists:shipping_methods,id',
            'weight_grams' => 'required|integer|min:1',
            'destination_province' => 'required|string',
            'receiver_email' => 'required|email',
            'receiver_address' => 'required|string',
        ]);

        // Add the sender_id from the authenticated user
        $validated['sender_id'] = Auth::id();

        $package = $this->packageService->createPackage($validated);

        return redirect()->route('packages.index')->with('success', 'Package registered successfully!');
    }

    /**
     * Show the details of a specific package.
     */
    public function show(Package $package)
    {
        // Authorization: Only sender (company) or receiver (user) can see details.
        if (Auth::user()->id !== $package->receiver_id && Auth::user()->company_id !== $package->company_id) {
             // Allow admins to see everything
             if (!Auth::user()->hasRole('Admin')) {
                abort(403);
             }
        }

        return view('packages.show', compact('package'));
    }
}
