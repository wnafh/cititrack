<?php

namespace App\Http\Controllers\Stocker;

use App\Http\Controllers\Controller;
use App\Http\Requests\StockInRequest;
use App\Models\AuditLog;
use App\Models\Product;
use App\Models\StockLog;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockInController extends Controller
{
    /**
     * Show the stock-in form and recent deliveries.
     */
    public function index(Request $request): View
    {
        $products  = Product::active()->orderBy('name')->get();
        $suppliers = Supplier::active()->orderBy('name')->get();

        // Recent deliveries by this stocker
        $recentLogs = StockLog::with(['product', 'supplier'])
            ->where('type', 'stock_in')
            ->latest()
            ->take(10)
            ->get();

        return view('stocker.stock-in', compact('products', 'suppliers', 'recentLogs'));
    }

    /**
     * Record a stock-in entry.
     */
    public function store(StockInRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($request, $validated) {
            $product = Product::findOrFail($validated['product_id']);

            $before = $product->stock_qty;
            $product->increment('stock_qty', $validated['qty']);
            $after = $product->fresh()->stock_qty;

            // Optionally update cost if provided
            if (! empty($validated['unit_cost'])) {
                $product->update(['cost' => $validated['unit_cost']]);
            }

            StockLog::create([
                'product_id'   => $product->id,
                'supplier_id'  => $validated['supplier_id'] ?? null,
                'user_id'      => auth()->id(),
                'type'         => 'stock_in',
                'qty'          => $validated['qty'],
                'stock_before' => $before,
                'stock_after'  => $after,
                'unit_cost'    => $validated['unit_cost'] ?? null,
                'reason'       => $validated['notes'] ?? null,
            ]);

            AuditLog::create([
                'user_id'    => auth()->id(),
                'action'     => 'stock.in',
                'model_type' => Product::class,
                'model_id'   => $product->id,
                'new_values' => [
                    'product'      => $product->name,
                    'qty_added'    => $validated['qty'],
                    'stock_before' => $before,
                    'stock_after'  => $after,
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        });

        return redirect()->route('stocker.stock-in.index')
            ->with('success', 'Stock-in recorded successfully.');
    }
}