<div>
    <p class="text-sm text-slate-600 mb-4">
        Once your account is deleted, all of its resources and data will be permanently deleted.
        Before deleting your account, please download any information you wish to keep.
    </p>

    <button type="button"
            onclick="document.getElementById('delete-account-form').classList.toggle('hidden')"
            class="btn-danger">
        Delete Account
    </button>

    <div id="delete-account-form" class="hidden mt-4 p-4 bg-red-50 border border-red-200 rounded">
        <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-4"
              onsubmit="return confirm('Are you sure you want to delete your account? This cannot be undone.');">
            @csrf
            @method('DELETE')

            <x-form-input
                name="password"
                label="Confirm Password"
                type="password"
                required
                hint="Enter your password to confirm deletion"
            />

            <div class="flex gap-3">
                <button type="submit" class="btn-danger">Yes, delete my account</button>
                <button type="button"
                        onclick="document.getElementById('delete-account-form').classList.add('hidden')"
                        class="btn-secondary">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>