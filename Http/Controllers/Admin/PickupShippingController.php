<?php

namespace Modules\PickupShipping\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstalledModule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PickupShippingController extends Controller
{
    /**
     * Show shipping method settings
     */
    public function index(): Response
    {
        $module = InstalledModule::where('slug', 'pickup-shipping')->firstOrFail();

        $defaultSettings = [
            'enabled' => true,
            'title' => 'Local Pickup',
            'description' => 'Pick up your order from our location',
            'pickup_address' => null,
            'pickup_instructions' => null,
            'sort_order' => 0,
        ];

        $settings = array_replace_recursive($defaultSettings, $module->settings ?? []);

        return Inertia::render('PickupShipping::Admin/Settings', [
            'module' => $module,
            'settings' => $settings,
            'defaultSettings' => $defaultSettings,
        ]);
    }

    /**
     * Update shipping method settings
     */
    public function update(Request $request): RedirectResponse
    {
        $module = InstalledModule::where('slug', 'pickup-shipping')->firstOrFail();

        $validated = $request->validate([
            'settings.enabled' => 'boolean',
            'settings.title' => 'required|string|max:255',
            'settings.description' => 'nullable|string|max:1000',
            'settings.pickup_address' => 'nullable|string|max:500',
            'settings.pickup_instructions' => 'nullable|string|max:2000',
            'settings.sort_order' => 'integer|min:0',
        ]);

        $module->update([
            'settings' => $validated['settings'],
        ]);

        return back()->with('success', 'Pickup settings updated successfully.');
    }
}