<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Menampilkan daftar customer.
     */
    public function index()
    {
        $customers = Customer::latest()->paginate(10);
        return view('admin.Masterdata.datacustomer.index', compact('customers'));
    }

    /**
     * Menampilkan form untuk menambah customer baru.
     */
    public function create()
    {
        return view('admin.Masterdata.datacustomer.create');
    }

    /**
     * Menyimpan customer baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_customer' => 'required|string|max:255',
            'email'         => 'nullable|email|max:255',
            'telepon'       => 'nullable|string|max:20',
            'alamat'        => 'nullable|string|max:500',
        ]);

        Customer::create($validatedData);

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail customer.
     */
    public function show(Customer $customer)
    {
        return view('admin.Masterdata.datacustomer.show', compact('customer'));
    }

    /**
     * Menampilkan form untuk mengedit customer.
     */
    public function edit(Customer $customer)
    {
        return view('admin.Masterdata.datacustomer.edit', compact('customer'));
    }

    /**
     * Memperbarui data customer di database.
     */
    public function update(Request $request, Customer $customer)
    {
        $validatedData = $request->validate([
            'nama_customer' => 'required|string|max:255',
            'email'         => 'nullable|email|max:255',
            'telepon'       => 'nullable|string|max:20',
            'alamat'        => 'nullable|string|max:500',
        ]);

        $customer->update($validatedData);

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil diperbarui.');
    }

    /**
     * Menghapus customer dari database.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil dihapus.');
    }
}
