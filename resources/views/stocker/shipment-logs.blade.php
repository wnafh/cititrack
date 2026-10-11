@extends('layouts.stocker')

@section('title', 'Shipment Logs')

@section('content')

<x-page-header title="Shipment Logs" subtitle="All stock-in deliveries" />

<form method="GET" class="card p-4 mb-4">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
        <x-filter-select
            name="supplier"
            :options="$suppliers->pluck('name', 'id')->toArray()"
            placeholder="All Suppliers"
        />
        <div>
            <input type="date" name="from" value="{{ request('from') }}" class="input" placeholder="From">
        </div>
        <div>
            <input type="date" name="to" value="{{ request('to') }}" class="input" placeholder="To">
        </div>
        <div class="md:col-span-2 flex gap-2">
            <button type="submit" class="btn-primary">Filter</button>
            <a href="{{ route('stocker.shipment-logs') }}" class="btn-secondary">Reset</a>
        </div>
    </div>
</form>

@if($logs->isEmpty())
    <x-empty-state title="No shipments found" message="Try adjusting your filters or record a new stock-in." />
@else
    <div class="card overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Product</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Supplier</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Recorded by</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Qty</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Stock After</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Unit Cost</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @foreach($logs as $log)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-xs text-slate-500">
                            {{ $log->created_at->format('M d, Y g:i A') }}
                        </td>
                        <td class="px-4 py-3 text-sm font-medium text-slate-800">
                            {{ $log->product->name ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-600">{{ $log->supplier->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-slate-600">{{ $log->user->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-right text-sm font-mono text-emerald-600">
                            +{{ $log->qty }}
                        </td>
                        <td class="px-4 py-3 text-right text-sm text-slate-600">{{ $log->stock_after }}</td>
                        <td class="px-4 py-3 text-right text-sm">
                            @if($log->unit_cost)
                                <x-money :amount="$log->unit_cost" />
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <x-pagination :paginator="$logs" />
@endif

@endsection