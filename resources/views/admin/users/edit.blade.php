@extends('layouts.admin')
@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
    <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" name="name" id="name"
                    value="{{ old('name', $user->name) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="e.g. John Doe" required>
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" id="email"
                    value="{{ old('email', $user->email) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="e.g. john@example.com" required>
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password (Leave blank to keep current)</label>
                <input type="password" name="password" id="password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Minimum 6 characters">
                @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-6">
                <label for="is_active" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="is_active" id="is_active"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="1" {{ old('is_active', $user->is_active) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('is_active', $user->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('is_active')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Plan Type -->
            <div class="mb-6">
                <label for="plan_type" class="block text-sm font-medium text-gray-700 mb-1">Plan Type</label>
                <select name="plan_type" id="plan_type"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="free" {{ old('plan_type', $user->plan_type) == 'free' ? 'selected' : '' }}>Free</option>
                    <option value="basic" {{ old('plan_type', $user->plan_type) == 'basic' ? 'selected' : '' }}>Basic</option>
                    <option value="premium" {{ old('plan_type', $user->plan_type) == 'premium' ? 'selected' : '' }}>Premium</option>
                </select>
                @error('plan_type')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Subscription Start -->
            <div class="mb-6">
                <label for="subscription_start" class="block text-sm font-medium text-gray-700 mb-1">Subscription Start</label>
                <input type="date" name="subscription_start" id="subscription_start"
                    value="{{ old('subscription_start', optional($user->subscription_start)->format('Y-m-d')) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('subscription_start')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Subscription End -->
            <div class="mb-6">
                <label for="subscription_end" class="block text-sm font-medium text-gray-700 mb-1">Subscription End</label>
                <input type="date" name="subscription_end" id="subscription_end"
                    value="{{ old('subscription_end', optional($user->subscription_end)->format('Y-m-d')) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('subscription_end')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Trial Ends At -->
            <div class="mb-6">
                <label for="trial_ends_at" class="block text-sm font-medium text-gray-700 mb-1">Trial Ends At</label>
                <input type="date" name="trial_ends_at" id="trial_ends_at"
                    value="{{ old('trial_ends_at', optional($user->trial_ends_at)->format('Y-m-d')) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('trial_ends_at')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Max MikroTiks -->
            <div class="mb-6">
                <label for="max_mikrotiks" class="block text-sm font-medium text-gray-700 mb-1">Max MikroTiks</label>
                <input type="number" name="max_mikrotiks" id="max_mikrotiks"
                    value="{{ old('max_mikrotiks', $user->max_mikrotiks) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="e.g. 3">
                @error('max_mikrotiks')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Max Customers -->
            <div class="mb-6">
                <label for="max_customers" class="block text-sm font-medium text-gray-700 mb-1">Max Customers</label>
                <input type="number" name="max_customers" id="max_customers"
                    value="{{ old('max_customers', $user->max_customers) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="e.g. 100">
                @error('max_customers')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Phone -->
            <div class="mb-6">
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <input type="text" name="phone" id="phone"
                    value="{{ old('phone', $user->phone) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="e.g. +628123456789">
                @error('phone')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Address -->
            <div class="mb-6">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <textarea name="address" id="address" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="e.g. Jl. Merdeka No. 1, Jakarta">{{ old('address', $user->address) }}</textarea>
                @error('address')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Roles -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Assign Roles</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach ($roles as $role)
                        <div class="flex items-center">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}" id="role_{{ $loop->index }}"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                {{ in_array($role->name, old('roles', $user->roles->pluck('name')->toArray() ?? [])) ? 'checked' : '' }}>
                            <label for="role_{{ $loop->index }}" class="ml-2 block text-sm text-gray-700">
                                {{ $role->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('roles')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('admin.users.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                    Cancel
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Update User
                </button>
            </div>
        </form>
    </div>
@endsection