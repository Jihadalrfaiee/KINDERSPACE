<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\User;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function index()
    {
        $salaries = Salary::with('user')->orderByDesc('year')->orderByDesc('month')->paginate(20);
        return view('finance.salaries.index', compact('salaries'));
    }

    public function create()
    {
        $users = User::where('role', 'teacher')->orWhere('role', 'admin')->get();
        return view('finance.salaries.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'base_salary' => 'required|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'net_salary' => 'required|numeric|min:0',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000',
            'paid_date' => 'nullable|date',
            'status' => 'required|in:pending,paid',
            'approved_by' => 'nullable|exists:users,id',
        ]);

        Salary::create($data);

        return redirect()->route('salaries.index')->with('success', 'تم إضافة قيد الراتب');
    }

    public function edit(Salary $salary)
    {
        $users = User::where('role', 'teacher')->orWhere('role', 'admin')->get();
        return view('finance.salaries.edit', compact('salary', 'users'));
    }

    public function update(Request $request, Salary $salary)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'base_salary' => 'required|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'net_salary' => 'required|numeric|min:0',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000',
            'paid_date' => 'nullable|date',
            'status' => 'required|in:pending,paid',
            'approved_by' => 'nullable|exists:users,id',
        ]);

        $salary->update($data);

        return redirect()->route('salaries.index')->with('success', 'تم تحديث قيد الراتب');
    }

    public function show(Salary $salary)
    {
        return view('finance.salaries.show', compact('salary'));
    }

    public function destroy(Salary $salary)
    {
        $salary->delete();
        return redirect()->route('salaries.index')->with('success', 'تم حذف قيد الراتب');
    }
}
