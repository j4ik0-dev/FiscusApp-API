<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses = Expense::orderBy('created_at', 'desc')->get();
        return response()->json($expenses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validamos que los datos vengan bien
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'type' => 'required|in:fixed,variable,ant', // Solo acepta lo que definimos
            'receipt' => 'nullable|image|max:2048', // Máximo 2MB por foto
            // Por ahora enviaremos el user_id manual hasta que hagamos el Login
            'user_id' => 'required|exists:users,id' 
        ]);

        $data = $request->all();

        // 2. Lógica de la Foto (Recibo)
        if ($request->hasFile('receipt')) {
            // Guarda en la carpeta 'public/receipts' y nos da la ruta única
            $path = $request->file('receipt')->store('receipts', 'public');
            $data['receipt_path'] = $path;
        }

        // 3. Crear el gasto en BD
        $expense = Expense::create($data);

        return response()->json([
            'message' => 'Gasto guardado correctamente',
            'data' => $expense
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
