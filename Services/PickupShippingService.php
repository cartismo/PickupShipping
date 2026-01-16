<?php

namespace Modules\PickupShipping\Services;

use App\Models\InstalledModule;

class PickupShippingService
{
    protected ?array $settings = null;

    /**
     * Get module settings
     */
    public function getSettings(): array
    {
        if ($this->settings === null) {
            $module = InstalledModule::where('slug', 'pickup-shipping')->first();

            $defaultSettings = [
                'enabled' => true,
                'title' => 'Local Pickup',
                'description' => 'Pick up your order from our location',
                'pickup_address' => null,
                'pickup_instructions' => null,
                'sort_order' => 0,
            ];

            $this->settings = array_replace_recursive($defaultSettings, $module?->settings ?? []);
        }

        return $this->settings;
    }

    /**
     * Check if module is enabled
     */
    public function isEnabled(): bool
    {
        return $this->getSettings()['enabled'] ?? false;
    }

    /**
     * Get shipping method title
     */
    public function getTitle(): string
    {
        return $this->getSettings()['title'] ?? 'Local Pickup';
    }

    /**
     * Get shipping method description
     */
    public function getDescription(): string
    {
        return $this->getSettings()['description'] ?? '';
    }

    /**
     * Get pickup address
     */
    public function getPickupAddress(): ?string
    {
        return $this->getSettings()['pickup_address'] ?? null;
    }

    /**
     * Get pickup instructions
     */
    public function getPickupInstructions(): ?string
    {
        return $this->getSettings()['pickup_instructions'] ?? null;
    }

    /**
     * Check if pickup is available
     */
    public function isAvailable(): bool
    {
        return $this->isEnabled();
    }

    /**
     * Get shipping method data for checkout
     */
    public function getShippingMethod(): ?array
    {
        if (!$this->isAvailable()) {
            return null;
        }

        return [
            'id' => 'pickup-shipping',
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'cost' => 0,
            'formatted_cost' => 'Free',
            'pickup_address' => $this->getPickupAddress(),
            'pickup_instructions' => $this->getPickupInstructions(),
        ];
    }
}