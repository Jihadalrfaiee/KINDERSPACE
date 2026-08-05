<?php

namespace App\Http\Controllers;

use App\Models\StudentFee;
use App\Models\Student;
use App\Models\Kindergarten;
use Illuminate\Http\Request;

class StudentFeeController extends Controller
{
    public function index()
    {
        $fees = StudentFee::with(['student', 'kindergarten'])->paginate(20);
        return view('finance.student_fees.index', compact('fees'));
    }

    public function create()
    {
        $students = Student::all();
        $kindergartens = Kindergarten::all();
        return view('finance.student_fees.create', compact('students', 'kindergartens'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'kindergarten_id' => 'required|exists:kindergartens,id',
            'academic_year' => 'required|string',
            'total_tuition' => 'required|numeric|min:0',
            'total_transportation' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_reason' => 'nullable|string',
            'net_amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'remaining_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,partial,paid',
        ]);

        StudentFee::create($data);

        return redirect()->route('student-fees.index')->with('success', 'تم إنشاء قيد رسوم الطالب');
    }

    public function edit(StudentFee $studentFee)
    {
        $students = Student::all();
        $kindergartens = Kindergarten::all();
        return view('finance.student_fees.edit', compact('studentFee', 'students', 'kindergartens'));
    }

    public function update(Request $request, StudentFee $studentFee)
    {
        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'kindergarten_id' => 'required|exists:kindergartens,id',
            'academic_year' => 'required|string',
            'total_tuition' => 'required|numeric|min:0',
            'total_transportation' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_reason' => 'nullable|string',
            'net_amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'remaining_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,partial,paid',
        ]);

        $studentFee->update($data);

        return redirect()->route('student-fees.index')->with('success', 'تم تحديث قيد الرسوم');
    }

    public function show(StudentFee $studentFee)
    {
        $studentFee->load('installments');
        return view('finance.student_fees.show', compact('studentFee'));
    }

    public function destroy(StudentFee $studentFee)
    {
        $studentFee->delete();
        return redirect()->route('student-fees.index')->with('success', 'تم حذف قيد الرسوم');
    }
}
