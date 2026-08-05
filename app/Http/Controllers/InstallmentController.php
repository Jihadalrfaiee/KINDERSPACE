<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\StudentFee;
use Illuminate\Http\Request;

class InstallmentController extends Controller
{
    public function index()
    {
        $installments = Installment::with(['student', 'studentFee'])->paginate(20);
        return view('finance.installments.index', compact('installments'));
    }

    public function create()
    {
        $fees = StudentFee::all();
        return view('finance.installments.create', compact('fees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_fee_id' => 'required|exists:student_fees,id',
            'student_id' => 'required|exists:students,id',
            'installment_number' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'nullable|in:pending,paid,partial',
        ]);

        Installment::create($data);

        return redirect()->route('installments.index')->with('success', 'تم إنشاء القسط');
    }

    public function edit(Installment $installment)
    {
        $fees = StudentFee::all();
        return view('finance.installments.edit', compact('installment', 'fees'));
    }

    public function update(Request $request, Installment $installment)
    {
        $data = $request->validate([
            'student_fee_id' => 'required|exists:student_fees,id',
            'student_id' => 'required|exists:students,id',
            'installment_number' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'nullable|in:pending,paid,partial',
            'paid_date' => 'nullable|date',
        ]);

        $installment->update($data);

        return redirect()->route('installments.index')->with('success', 'تم تحديث القسط');
    }

    public function show(Installment $installment)
    {
        return view('finance.installments.show', compact('installment'));
    }

    public function destroy(Installment $installment)
    {
        $installment->delete();
        return redirect()->route('installments.index')->with('success', 'تم حذف القسط');
    }
}
