<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function index(Request $request)
{
    $user = Auth::user();

    // Get page size (default 10)
    $perPage = $request->get('per_page', 10);

    if ($user->role === 'admin') {
        $expenses = Expense::with('category', 'user')
            ->latest()
            ->paginate($perPage);
    } else {
        $expenses = Expense::with('category', 'user')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate($perPage);
    }

    return response()->json($expenses);
}

    // public function index()
    // {
    //     $user = Auth::user();
    //     if ($user->role === 'admin') {
    //         $expenses = Expense::with('category', 'user')->get();
    //     } else {
    //         $expenses = Expense::with('category', 'user')->where('user_id', $user->id)->latest()->get();
    //     }

    //     return response()->json([
    //         'expenses' => $expenses,
    //     ]);
    // }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'payment_mode' => 'required|string',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachment', 'public');
        }

        $expense = Expense::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'subcategory' => $request->subcategory,
            'expense_date' => $request->expense_date,
            'amount' => $request->amount,
            'payment_mode' => $request->payment_mode,
            'description' => $request->description,
            'attachment' => $path,
        ]);

        return response()->json(['message' => 'Expense added successfully', 'expense' => $expense]);
    }

    // public function update(Request $request, $id)
    // {
    //     $expense = Expense::findOrFail($id);

    //     if (Auth::user()->role !== 'admin' && $expense->user_id !== Auth::id()) {
    //         return response()->json(['error' => 'Unauthorized'], 403);
    //     }
    //     $request->validate([
    //         'category_id' => 'required|exists:categories,id',
    //         'expense_date' => 'required|date',
    //         'amount' => 'required|numeric|min:0',
    //         'payment_mode' => 'required|string',
    //     ]);

    //     $expense->update($request->all());

    //     return response()->json(['message' => 'Expense updated successfully']);
    // }

public function update(Request $request, $id)
{
    $expense = Expense::findOrFail($id);

    if (Auth::user()->role !== 'admin' && $expense->user_id !== Auth::id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $request->validate([
        'category_id'   => 'required|exists:categories,id',
        'expense_date'  => 'required|date',
        'amount'        => 'required|numeric|min:0',
        'payment_mode'  => 'required|string|in:Cash,Credit Card,Debit Card,Bank Transfer,Mobile Wallet',
    ]);

    $expense->update([
        'category_id'   => $request->category_id,
        'expense_date'  => $request->expense_date,
        'amount'        => $request->amount,
        'payment_mode'  => $request->payment_mode,
        'description'   => $request->description,
        'subcategory'   => $request->subcategory,
    ]);

    if ($request->hasFile('attachment')) {
        $path = $request->file('attachment')->store('attachments', 'public');
        $expense->attachment = $path;
        $expense->save();
    }

    return response()->json(['message' => 'Expense updated successfully']);
}



    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);

        if (Auth::user()->role !== 'admin' && $expense->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($expense->attachment && Storage::disk('public')->exists($expense->attachment)) {
            Storage::disk('public')->delete($expense->attachment);
        }

        $expense->delete();

        return response()->json(['message' => 'Expense deleted successfully']);
    }
}
