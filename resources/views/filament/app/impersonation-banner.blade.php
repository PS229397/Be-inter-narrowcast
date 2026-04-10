@php($impersonatedCustomerName = auth()->user()?->customer?->name)

@if (session()->has('admin_impersonator_id') && filled($impersonatedCustomerName))
    <div class="mx-4 mt-4 rounded-lg border border-warning-200 bg-warning-50 px-4 py-3 text-sm text-warning-900">
        <div class="flex items-center justify-between gap-4">
            <span>Impersonating {{ $impersonatedCustomerName }}</span>

            <form method="POST" action="{{ route('impersonation.end') }}">
                @csrf
                <button
                    type="submit"
                    class="rounded bg-warning-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-warning-700"
                >
                    End session
                </button>
            </form>
        </div>
    </div>
@endif
