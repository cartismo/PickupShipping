<script setup>
import { ref, computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import {
    BuildingStorefrontIcon,
    ArrowLeftIcon,
    ArrowPathIcon,
    CheckCircleIcon,
    XCircleIcon,
    MapPinIcon,
    Cog6ToothIcon,
    InformationCircleIcon,
    ClockIcon,
    DocumentTextIcon,
} from '@heroicons/vue/24/outline';
import { CheckIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
    module: Object,
    settings: Object,
    defaultSettings: Object,
});

const form = useForm({
    settings: JSON.parse(JSON.stringify(props.settings)),
});

const submit = () => {
    form.put(route('admin.shipping.pickup.settings.update'));
};

const resetAll = () => {
    if (confirm('Reset all settings to defaults?')) {
        form.settings = JSON.parse(JSON.stringify(props.defaultSettings));
    }
};

const hasChanges = computed(() => {
    return JSON.stringify(form.settings) !== JSON.stringify(props.settings);
});

const hasAddress = computed(() => {
    return form.settings.pickup_address && form.settings.pickup_address.trim().length > 0;
});
</script>

<template>
    <AdminLayout :title="`${module.name} Settings`">
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <Link
                        :href="route('admin.modules.installed.index')"
                        class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                    >
                        <ArrowLeftIcon class="w-5 h-5" />
                    </Link>
                    <div class="flex items-center space-x-3">
                        <div class="p-3 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl shadow-lg">
                            <BuildingStorefrontIcon class="w-6 h-6 text-white" />
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">{{ module.name }}</h1>
                            <p class="text-sm text-gray-500">Shipping Method Configuration</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <span v-if="hasChanges" class="text-sm text-amber-600 font-medium">
                        Unsaved changes
                    </span>
                    <button
                        type="button"
                        @click="resetAll"
                        class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors"
                    >
                        <ArrowPathIcon class="w-4 h-4 inline mr-2" />
                        Reset
                    </button>
                    <button
                        type="submit"
                        form="settings-form"
                        :disabled="form.processing || !hasChanges"
                        class="px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-amber-600 to-orange-600 rounded-xl hover:from-amber-700 hover:to-orange-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-lg shadow-amber-500/25"
                    >
                        <CheckIcon class="w-4 h-4 inline mr-2" />
                        Save Changes
                    </button>
                </div>
            </div>
        </template>

        <form id="settings-form" @submit.prevent="submit">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Left Sidebar - Module Info -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Status Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-5 border-b border-gray-100">
                            <h3 class="font-semibold text-gray-900">Module Status</h3>
                        </div>
                        <div class="p-5 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Status</span>
                                <span
                                    :class="form.settings.enabled ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'"
                                    class="px-3 py-1 text-xs font-semibold rounded-full"
                                >
                                    {{ form.settings.enabled ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Version</span>
                                <span class="text-sm font-mono text-gray-900">v{{ module.installed_version }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Type</span>
                                <span class="text-sm text-gray-900">Shipping</span>
                            </div>
                        </div>
                    </div>

                    <!-- Location Status -->
                    <div :class="hasAddress ? 'bg-gradient-to-br from-amber-500 to-orange-600' : 'bg-gradient-to-br from-gray-400 to-gray-500'" class="rounded-2xl shadow-lg p-5 text-white">
                        <div class="flex items-center space-x-3 mb-3">
                            <MapPinIcon class="w-8 h-8 opacity-80" />
                            <div>
                                <p class="text-sm opacity-80">Pickup Location</p>
                                <p class="text-lg font-bold">{{ hasAddress ? 'Configured' : 'Not Set' }}</p>
                            </div>
                        </div>
                        <div class="pt-3 border-t border-white/20">
                            <p class="text-sm opacity-80 line-clamp-2">
                                {{ hasAddress ? form.settings.pickup_address : 'Add your pickup address in the settings' }}
                            </p>
                        </div>
                    </div>

                    <!-- Help Card -->
                    <div class="bg-amber-50 rounded-2xl p-5 border border-amber-100">
                        <div class="flex items-start space-x-3">
                            <InformationCircleIcon class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" />
                            <div>
                                <h4 class="text-sm font-medium text-amber-900">Local Pickup</h4>
                                <p class="text-sm text-amber-700 mt-1">
                                    Allow customers to collect orders from your physical location. Great for reducing shipping costs.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Settings Forms -->
                <div class="lg:col-span-3 space-y-6">
                    <!-- Enable/Disable Toggle -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div :class="form.settings.enabled ? 'bg-amber-100' : 'bg-gray-100'" class="p-3 rounded-xl transition-colors">
                                    <component :is="form.settings.enabled ? CheckCircleIcon : XCircleIcon"
                                        :class="form.settings.enabled ? 'text-amber-600' : 'text-gray-400'"
                                        class="w-6 h-6"
                                    />
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Enable Local Pickup</h3>
                                    <p class="text-sm text-gray-500">Allow customers to collect orders in person</p>
                                </div>
                            </div>
                            <button
                                type="button"
                                @click="form.settings.enabled = !form.settings.enabled"
                                :class="form.settings.enabled ? 'bg-amber-500' : 'bg-gray-300'"
                                class="relative inline-flex h-7 w-12 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2"
                            >
                                <span
                                    :class="form.settings.enabled ? 'translate-x-5' : 'translate-x-0'"
                                    class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out"
                                />
                            </button>
                        </div>
                    </div>

                    <!-- General Settings -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <div class="flex items-center space-x-3">
                                <Cog6ToothIcon class="w-5 h-5 text-gray-400" />
                                <h2 class="font-semibold text-gray-900">General Settings</h2>
                            </div>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Title -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Display Title <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        v-model="form.settings.title"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                                        placeholder="e.g., Local Pickup"
                                    />
                                </div>

                                <!-- Sort Order -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                                    <input
                                        type="number"
                                        v-model.number="form.settings.sort_order"
                                        min="0"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                                        placeholder="0"
                                    />
                                    <p class="mt-1.5 text-xs text-gray-500">Lower numbers appear first</p>
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea
                                    v-model="form.settings.description"
                                    rows="2"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors resize-none"
                                    placeholder="Short description shown at checkout..."
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Pickup Location -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <div class="flex items-center space-x-3">
                                <MapPinIcon class="w-5 h-5 text-gray-400" />
                                <h2 class="font-semibold text-gray-900">Pickup Location</h2>
                            </div>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Pickup Address -->
                            <div class="bg-amber-50 rounded-xl p-5 border border-amber-100">
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    <MapPinIcon class="w-4 h-4 inline mr-1 text-amber-600" />
                                    Pickup Address <span class="text-red-500">*</span>
                                </label>
                                <textarea
                                    v-model="form.settings.pickup_address"
                                    rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors resize-none bg-white"
                                    placeholder="123 Main Street&#10;City, State 12345&#10;Country"
                                ></textarea>
                                <p class="mt-2 text-xs text-amber-700">
                                    This address will be shown to customers who select local pickup.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Pickup Instructions -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <div class="flex items-center space-x-3">
                                <DocumentTextIcon class="w-5 h-5 text-gray-400" />
                                <h2 class="font-semibold text-gray-900">Pickup Instructions</h2>
                            </div>
                        </div>
                        <div class="p-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <ClockIcon class="w-4 h-4 inline mr-1 text-gray-500" />
                                    Instructions for Customers
                                </label>
                                <textarea
                                    v-model="form.settings.pickup_instructions"
                                    rows="4"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors resize-none"
                                    placeholder="Please bring your order confirmation email.&#10;Pickup hours: Mon-Fri 9am-5pm&#10;Parking available at the back of the building."
                                ></textarea>
                                <p class="mt-2 text-xs text-gray-500">
                                    Include pickup hours, what to bring, parking info, and any other helpful details.
                                </p>
                            </div>

                            <!-- Preview -->
                            <div v-if="form.settings.pickup_instructions" class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Preview</label>
                                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                                    <p class="text-sm text-amber-800 whitespace-pre-wrap">{{ form.settings.pickup_instructions }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </AdminLayout>
</template>