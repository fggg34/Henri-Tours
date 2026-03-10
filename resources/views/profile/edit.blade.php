<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
            <div class="mb-10">
                <h1 class="text-2xl font-bold text-gray-900">{{ __('Profile') }}</h1>
                <p class="mt-1 text-gray-600">{{ __('Update your account information and preferences.') }}</p>
            </div>

            <div class="space-y-8">
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden p-6">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden p-6">
                    @include('profile.partials.update-password-form')
                </div>

                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden p-6">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
