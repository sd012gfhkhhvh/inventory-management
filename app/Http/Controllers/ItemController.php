<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use PDF;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Item::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $items = $query->paginate(7);

        // Fetch other data for the dashboard
        $totalItems = Item::count();
        $totalQuantity = Item::sum('quantity');
        $lowStockCount = Item::where('quantity', '<', 10)->count();
        $recentTransactions = Transaction::count(); // Or use a specific timeframe if needed

        $lowStockItems = Item::where('quantity', '<', 10)->take(5)->get();

        return view('items.index', compact('items', 'search', 'totalItems', 'totalQuantity', 'lowStockCount', 'recentTransactions', 'lowStockItems'));
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'quantity' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();

        try {
            $item = Item::create($validatedData);

            // Record the item creation transaction
            Transaction::create([
                'item_id' => $item->id,
                'type' => 'creation',
                'quantity' => $item->quantity,
                'notes' => 'Item created with initial quantity of ' . $item->quantity,
            ]);

            // If initial quantity is greater than 0, record an additional 'initial stock' transaction
            if ($item->quantity > 0) {
                Transaction::create([
                    'item_id' => $item->id,
                    'type' => 'initial stock',
                    'quantity' => $item->quantity,
                    'notes' => 'Initial stock added on item creation',
                ]);
            }

            DB::commit();

            return redirect()->route('items.show', $item)->with('success', 'Item created successfully and transaction recorded.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while creating the item. Please try again.');
        }
    }

    public function show(Item $item)
    {
        $transactions = $item->transactions()->orderBy('created_at', 'desc')->paginate(7);
        return view('items.show', compact('item', 'transactions'));
    }

    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
        ]);

        $item->update($validatedData);

        return redirect()->route('items.show', $item)->with('success', 'Item updated successfully');
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }

    public function dispatch(Request $request, Item $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $item->quantity,
            'notes' => 'nullable|string',
        ]);

        $item->quantity -= $request->quantity;
        $item->save();

        Transaction::create([
            'item_id' => $item->id,
            'quantity' => $request->quantity,
            'type' => 'dispatch',
            'notes' => $request->notes ?? 'Dispatched ' . $request->quantity . ' items',
        ]);

        $referer = URL::previous();
        $showRoute = route('items.show', $item);

        if ($referer === $showRoute) {
            return redirect()->route('items.show', $item)->with('success', 'Item dispatched successfully.');
        } else {
            return redirect()->route('items.index')->with('success', 'Item dispatched successfully.');
        }
    }

    public function restock(Request $request, Item $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $item->quantity += $request->quantity;
        $item->save();

        Transaction::create([
            'item_id' => $item->id,
            'quantity' => $request->quantity,
            'type' => 'restock',
            'notes' => $request->notes ?? 'Restocked ' . $request->quantity . ' items',
        ]);

        $referer = URL::previous();
        $showRoute = route('items.show', $item);

        if ($referer === $showRoute) {
            return redirect()->route('items.show', $item)->with('success', 'Item restocked successfully.');
        } else {
            return redirect()->route('items.index')->with('success', 'Item restocked successfully.');
        }
    }

    public function export()
    {
        // Implement export functionality (e.g., CSV export)
        // This is just a placeholder, you'll need to implement the actual export logic
        return redirect()->route('items.index')->with('success', 'Inventory exported successfully.');
    }

    public function generatePdf()
    {
        $items = Item::all();
        $pdf = PDF::loadView('reports.pdf', compact('items'));
        return $pdf->download('inventory_report.pdf');
    }
}