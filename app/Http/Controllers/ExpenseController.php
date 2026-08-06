<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Kindergarten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('kindergarten')->latest()->paginate(20);
        return view('finance.expenses.index', compact('expenses'));
    }

    public function create()
    {
        $kindergartens = Kindergarten::all();
        return view('finance.expenses.create', compact('kindergartens'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kindergarten_id' => 'required|exists:kindergartens,id',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'receipt_image' => 'nullable|string',
        ]);

        $data['created_by'] = Auth::id();

        Expense::create($data);

        return redirect()->route('expenses.index')->with('success', 'تم إضافة المصروف');
    }

    public function edit(Expense $expense)
    {
        $kindergartens = Kindergarten::all();
        return view('finance.expenses.edit', compact('expense', 'kindergartens'));
    }

    public function update(Request $request, Expense $expense)
    {
        $data = $request->validate([
            'kindergarten_id' => 'required|exists:kindergartens,id',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'receipt_image' => 'nullable|string',
        ]);

        $expense->update($data);

        return redirect()->route('expenses.index')->with('success', 'تم تعديل المصروف');
    }

    public function show(Expense $expense)
    {
        return view('finance.expenses.show', compact('expense'));
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'تم حذف المصروف');
    }
}
