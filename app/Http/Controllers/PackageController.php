<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\User;
use App\Models\ShippingMethod;
use App\Services\PackageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;

class PackageController extends Controller
{
    protected $packageService;

    public function __construct(PackageService $packageService)
    {
        $this->packageService = $packageService;
    }

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

    public function create()
    {
        $methods = ShippingMethod::all();
        $provinces = ['Groningen', 'Friesland', 'Drenthe', 'Overijssel', 'Flevoland', 'Gelderland', 'Utrecht', 'Noord-Holland', 'Zuid-Holland', 'Zeeland', 'Noord-Brabant', 'Limburg'];
        return view('packages.create', compact('methods', 'provinces'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'shipping_method_id' => 'required|exists:shipping_methods,id',
            'weight_grams' => 'required|integer|min:1',
            'destination_province' => 'required|string',
            'receiver_email' => 'required|email',
            'receiver_street' => 'required|string',
            'receiver_city' => 'required|string',
            'receiver_house_number' => 'required|integer',
        ]);

        $validated['sender_id'] = Auth::id();
        $package = $this->packageService->createPackage($validated);
        return redirect()->route('packages.show', $package->id)->with('success', 'Package registered successfully! You can now print the QR code.');
    }

    public function show(Package $package)
    {
        if (Auth::user()->id !== $package->receiver_id && Auth::user()->company_id !== $package->company_id) {
            if (!Auth::user()->hasRole('Admin')) {
                abort(403);
            }
        }

        $qrCode = new QrCode($package->tracking_number);
        $svgWriter = new \Endroid\QrCode\Writer\SvgWriter();
        $svgResult = $svgWriter->write($qrCode);
        $qrSvg = $svgResult->getString();

        // Use simple string replacement to ensure dimensions are present for PDF engines.
        $qrSvg = str_replace('width="', 'width="300" ', $qrSvg);
        $qrSvg = str_replace('height="', 'height="300" ', $qrSvg);

        return view('packages.show', compact('package', 'qrSvg'));
    }

    public function showQrCode(Package $package)
    {
        $qrCode = new QrCode($package->tracking_number);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        return response($result->getString(), 200)->header('Content-Type', 'image/png');
    }
}
