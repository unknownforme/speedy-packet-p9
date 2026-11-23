<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    /**
     * Start impersonating a user.
     */
    public function start(User $user)
    {
        // Ensure only admins can impersonate
        if (!Auth::user()->hasRole('Admin')) {
            abort(403, 'Unauthorized action.');
        }

        // Store the original admin ID to allow stopping impersonation
        session(['original_admin_id' => Auth::id()]);
        session(['impersonated_user_id' => $user->id]);

        // Log in as the target user
        Auth::login($user);

        return redirect()->route('dashboard')->with('status', 'You are now impersonating ' . $user->name);
    }

    /**
     * Stop impersonating and return to the admin account.
     */
    public function stop()
    {
        if (session()->has('impersonated_user_id')) {
            $adminId = session()->get('original_admin_id');
            
            // Clear the impersonation session
            session()->forget(['impersonated_user_id', 'original_admin_id']);

            // Log back in as the admin
            Auth::login(User::findOrFail($adminId));

            return redirect()->route('dashboard')->with('status', 'You are no longer impersonating.');
        }

        return redirect()->route('dashboard');
    }
}
