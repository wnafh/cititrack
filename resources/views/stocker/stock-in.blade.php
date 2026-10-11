@extends('layouts.stocker')

@section('title', 'Record Stock-In')

@section('content')

<x-page-header title="Record Stock-In" subtitle="Log a new delivery from a supplier" />

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-1">
        <form method="POST" action="{{ route('stocker.stock-in.store') }}">
            @csrf

            <x-section-card title="Delivery Details">
                <div class="space-y-4">

                    <x-form-select
                        name="product_id"
                        label="Product"
                        :options="$products->pluck('name', 'id')->toArray()"
                        :value="old('product_id')"
                        required
                        placeholder="-- Select product --"
                    />

                    <x-form-select
                        name="supplier_id"
                        label="Supplier (optional)"
                        :options="$suppliers->pluck('name', 'id')->toArray()"
                        :value="old('supplier_id')"
                        placeholder="-- None --"
                    />

                    <x-form-input name="qty" label="Quantity Received" type="number" min="1"
                                  :value="old('qty', 1)" required />

                    <x-form-input name="unit_cost" label="Unit Cost (₱, optional)" type="number"
                                  step="0.01" min="0" :value="old('unit_cost')"
                                  hint="Updates product cost if provided" />

                    <x-form-textarea name="notes" label="Notes (optional)" :value="old('notes')" />

                    <button type="submit" class="btn-primary w-full justify-center">
                        Record Stock-In
                    </button>

                </div>
            </x-section-card>
        </form>
    </div>

    <div class="lg:col-span-2">
        <x-section-card title="Recent Deliveries" subtitle="Last 10 stock-in entries">
            @if($recentLogs->isEmpty())
                <x-empty-state title="No deliveries yet" message="Record your first stock-in on the left." />
            @else
                <table class="w-full text-sm">
                    <thead class="text-xs uppercase text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="py-2 text-left">Product</th>
                            <th class="py-2 text-left">Supplier</th>
                            <th class="py-2 text-right">Qty</th>
                            <th class="py-2 text-right">Stock After</th>
                            <th class="py-2 text-right">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($recentLogs as $log)
                            <tr>
                                <td class="py-2 text-slate-800 font-medium">{{ $log->product->name ?? '—' }}</td>
                                <td class="py-2 text-slate-600 text-xs">{{ $log->supplier->name ?? '—' }}</td>
                                <td class="py-2 text-right text-emerald-600 font-mono">+{{ $log->qty }}</td>
                                <td class="py-2 text-right text-slate-600">{{ $log->stock_after }}</td>
                                <td class="py-2 text-right text-xs text-slate-500">
                                    {{ $log->created_at->format('M d, g:i A') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </x-section-card>
    </div>

</div>

@endsection