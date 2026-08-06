<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Installment;
use App\Models\Expense;
use App\Models\Salary;
use App\Models\StudentFee;
use App\Models\Student;

class ReportsController extends Controller
{
    // Monthly finance report: expected income (installment sums by due_date), expenses, salaries
    public function monthly(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $start = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();

        // Expected income: sum of installments due in month
        $expectedIncome = Installment::whereBetween('due_date', [$start->toDateString(), $end->toDateString()])->sum('amount');

        // Expenses in the month
        $expenses = Expense::whereBetween('date', [$start->toDateString(), $end->toDateString()])->sum('amount');

        // Salaries for the month/year
        $salaries = Salary::where('month', $start->month)->where('year', $start->year)->sum('net_salary');

        return view('finance.reports.monthly', compact('start', 'end', 'expectedIncome', 'expenses', 'salaries'));
    }

    // Student balance report
    public function studentBalance(Student $student)
    {
        $fees = StudentFee::where('student_id', $student->id)->get();

        $totalNet = $fees->sum('net_amount');
        $totalPaid = $fees->sum('paid_amount');
        $totalRemaining = $fees->sum('remaining_amount');

        $dueInstallments = Installment::where('student_id', $student->id)->where('status', 'pending')->orderBy('due_date')->get();

        return view('finance.reports.student', compact('student', 'fees', 'totalNet', 'totalPaid', 'totalRemaining', 'dueInstallments'));
    }
}
