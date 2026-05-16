<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return match (auth()->user()->role) {
            UserRole::Admin => redirect()->route('admin.dashboard'),
            UserRole::Client => redirect()->route('client.dashboard'),
            UserRole::Freelancer => redirect()->route('freelancer.dashboard'),
        };
    }
}
