@extends('layouts.admin')

@section('title', 'Edit Staff')

@section('content')

<x-page-header title="Edit Staff" :subtitle="$user->name" />

<form method="POST" action="{{ route('admin.users.update', $user) }}" class="max-w-2xl">
    @csrf
    @method('PUT')

    <x-section-card title="Account Details">
        <div class="space-y-4">

            <x-form-input name="name" label="Full Name" :value="old('name', $user->name)" required />

            <x-form-input name="email" label="Email Address" type="email" :value="old('email', $user->email)" required />

            <x-form-select
                name="role"
                label="Role"
                :options="['admin' => 'Admin', 'cashier' => 'Cashier', 'stocker' => 'Stocker']"
                :value="old('role', $user->role)"
                required
            />

            <div class="border-t border-slate-200 pt-4">
                <p class="text-sm font-medium text-slate-700 mb-3">Change Password</p>
                <p class="text-xs text-slate-500 mb-3">Leave blank to keep the current password.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-form-input name="password" label="New Password" type="password"
                                  hint="Minimum 8 characters" />
                    <x-form-input name="password_confirmation" label="Confirm Password" type="password" />
                </div>
            </div>

            <div class="border-t border-slate-200 pt-4">
                <label class="inline-flex items-center">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1"
                           @checked(old('is_active', $user->is_active))
                           @disabled($user->id === auth()->id())
                           class="rounded border-slate-300 text-amber-500 focus:ring-amber-500">
                    <span class="ml-2 text-sm text-slate-700">Active account</span>
                </label>
                @if($user->id === auth()->id())
                    <p class="text-xs text-amber-600 mt-2">You cannot deactivate your own account.</p>
                @endif
            </div>

        </div>
    </x-section-card>

    <div class="mt-6 flex gap-3">
        <button type="submit" class="btn-primary">Update Account</button>
        <a href="{{ route('admin.users.index') }}" class="btn-secondary">Cancel</a>
    </div>
</form>

@endsection