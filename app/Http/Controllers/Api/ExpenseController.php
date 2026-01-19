<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function index()
    {
        return Expense::where('user_id', auth()->id())->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'type' => 'required|in:fixed,variable,ant',
            'receipt' => 'nullable|image|max:2048',
        ]);

        $expense = Expense::create([
            'title' => $request->title,
            'amount' => $request->amount,
            'type' => $request->type,
            'frequency' => $request->frequency,
            'payment_date' => $request->payment_date,
            'user_id' => auth()->id()
        ]);

        return response()->json($expense, 201);
    }

    public function show($id)
    {
        $expense = Expense::where('user_id', auth()->id())->findOrFail($id);
        return response()->json($expense);
    }

    public function update(Request $request, string $id)
    {
        $expense = Expense::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'amount' => 'sometimes|numeric',
            'type' => 'sometimes|in:fixed,variable,ant',
            'receipt' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        $expense->update($data);

        return response()->json([
            'message' => 'Gasto actualizado',
            'data' => $expense
        ]);
    }

    public function destroy(string $id)
    {
        $expense = Expense::where('user_id', auth()->id())->findOrFail($id);
        
        $expense->delete();

        return response()->json(['message' => 'Gasto eliminado correctamente']);
    }
}