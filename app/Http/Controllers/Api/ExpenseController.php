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
    public function show($id)
    {
        $expense = Expense::findOrFail($id);
        return response()->json($expense);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $expense = Expense::findOrFail($id);

        // Validamos (usamos 'sometimes' para que no sea obligatorio enviar todo de nuevo)
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'amount' => 'sometimes|numeric',
            'type' => 'sometimes|in:fixed,variable,ant',
            'receipt' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        // Lógica inteligente para la foto:
        // Si suben una nueva, borramos la vieja y guardamos la nueva.
        if ($request->hasFile('receipt')) {
            // Borrar foto vieja si existe
            if ($expense->receipt_path) {
                Storage::disk('public')->delete($expense->receipt_path);
            }
            // Guardar nueva
            $data['receipt_path'] = $request->file('receipt')->store('receipts', 'public');
        }

        $expense->update($data);

        return response()->json([
            'message' => 'Gasto actualizado',
            'data' => $expense
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $expense = Expense::findOrFail($id);
        
        // Como usamos SoftDeletes, esto solo lo marca como "borrado" en la BD
        // pero no borra el registro ni la foto (ideal para recuperar datos después).
        $expense->delete();

        return response()->json(['message' => 'Gasto eliminado correctamente']);
    }
}
