<?php

namespace App\Http\Controllers;

use App\Models\StudentFee;
use App\Models\Student;
use App\Models\Kindergarten;
use App\Models\FeeStructure;
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

        $studentFee = StudentFee::create($data);

        // حاول توليد الأقساط تلقائياً عند وجود هيكل رسوم متاح
        try {
            $this->generateInstallmentsForStudentFee($studentFee);
        } catch (\Throwable $e) {
            // لا نُحبط العملية إذا فشل التوليد التلقائي، فقط نسجل رسالة في السجلات
            logger()->warning('Failed to auto-generate installments for student fee: ' . $e->getMessage());
        }

        return redirect()->route('student-fees.index')->with('success', 'تم إنشاء قيد رسوم الطالب');
    }

    /**
     * Generate installments for a student fee.
     * Uses FeeStructure.installments_count when available, otherwise falls back to settings.default_installments or 5.
     */
    private function generateInstallmentsForStudentFee(StudentFee $studentFee)
    {
        // حاول الحصول على هيكل الرسوم للروضة والسنة
        $feeStructure = FeeStructure::where('kindergarten_id', $studentFee->kindergarten_id)
            ->where('academic_year', $studentFee->academic_year)
            ->where('is_active', true)
            ->first();

        if ($feeStructure) {
            $count = (int) $feeStructure->installments_count;
            $scheduleType = $feeStructure->schedule_type ?? 'monthly';
            $firstOffset = $feeStructure->first_due_offset_days ?? 7;
            $intervalMonths = $feeStructure->interval_months ?? 1;
            $customSchedule = $feeStructure->custom_schedule ?? null;
        } else {
            // اقرأ من الإعدادات إن وجدت
            $default = (int) optional(\App\Models\Setting::where('key', 'default_installments')->first())->value ?: 5;
            $count = $default > 0 ? $default : 5;
            $scheduleType = 'monthly';
            $firstOffset = 7;
            $intervalMonths = 1;
            $customSchedule = null;
        }

        if ($count < 1) {
            $count = 1;
        }

        // حذف أي أقساط قديمة مرتبطة (لتجنب التكرار)
        $studentFee->installments()->delete();

        $net = (float) $studentFee->net_amount;
        $base = floor($net / $count * 100) / 100; // round down to 2 decimals
        $remainder = round($net - ($base * $count), 2);

        $startDate = now()->addDays($firstOffset); // use configured offset

        // If custom schedule provided (array of dates), use those dates
        $customDates = null;
        if ($scheduleType === 'custom' && $customSchedule) {
            if (is_array($customSchedule)) {
                $customDates = $customSchedule;
            } else {
                try {
                    $customDates = json_decode($customSchedule, true);
                } catch (\Throwable $e) {
                    $customDates = null;
                }
            }
        }

        for ($i = 1; $i <= $count; $i++) {
            $amount = $base;
            // أضف الباقي إلى آخر قسط
            if ($i === $count) {
                $amount = round($amount + $remainder, 2);
            }

            $dueDate = null;
            if ($customDates && isset($customDates[$i - 1])) {
                $dueDate = $customDates[$i - 1];
            } else {
                // monthly schedule with custom interval
                $dueDate = $startDate->copy()->addMonths(($i - 1) * max(1, (int)$intervalMonths))->toDateString();
            }

            $studentFee->installments()->create([
                'student_id' => $studentFee->student_id,
                'installment_number' => $i,
                'amount' => $amount,
                'due_date' => $dueDate,
                'status' => 'pending',
            ]);
        }

        // تأكد من تحديث الأرصدة
        $studentFee->update([
            'paid_amount' => $studentFee->paid_amount ?? 0,
            'remaining_amount' => $studentFee->net_amount - ($studentFee->paid_amount ?? 0),
        ]);

        return true;
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
