<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create', ['mode' => 'create', 'supplier' => new Supplier()]);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        // Handle file uploads
        foreach ($request->files as $field => $file) {
            if ($request->hasFile($field) && $request->file($field)->isValid()) {
                $data[$field] = $request->file($field)->store('uploads', 'public');
            }
        }

        Supplier::create($data);
        return redirect()->route('suppliers.index')->with('success', 'Created successfully!');
    }

    public function show(Supplier $supplier)
    {
        return view('suppliers.view', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', ['mode' => 'edit', 'supplier' => $supplier]);
    }

    public function update(Request $request, Supplier $supplier)
    {
        $data = $request->all();

        // Handle file uploads
        foreach ($request->files as $field => $file) {
            if ($request->hasFile($field) && $request->file($field)->isValid()) {
                $data[$field] = $request->file($field)->store('uploads', 'public');
            }
        }

        $supplier->update($data);
        return redirect()->route('suppliers.index')->with('success', 'Updated successfully!');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Deleted successfully!');
    }
}