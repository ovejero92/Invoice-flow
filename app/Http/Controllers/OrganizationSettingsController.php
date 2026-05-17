<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrganizationSettingsController extends Controller
{
    public function edit(Request $request): View
    {
        $organization = $request->user()->organization;
        abort_unless($organization, 403);

        return view('organization.settings', compact('organization'));
    }

    public function update(Request $request): RedirectResponse
    {
        $organization = $request->user()->organization;
        abort_unless($organization, 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'legal_name' => ['nullable', 'string', 'max:255'],
            'tax_id' => ['nullable', 'string', 'max:64'],
            'legal_address' => ['nullable', 'string', 'max:500'],
            'invoice_series_prefix' => ['nullable', 'string', 'max:16'],
            'default_tax_rate' => ['required', 'integer', 'min:0', 'max:100'],
            'country_code' => ['required', 'string', 'size:2'],
        ]);

        $organization->update($data);

        return back()->with('ok', 'Datos fiscales del estudio actualizados.');
    }
}
