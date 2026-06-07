<?php

namespace App\Http\Controllers;

use App\Models\{Student, Section, Category, Kindergarten, User};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    /**
     * عرض قائمة الطلاب
     */
    public function index(Request $request)
    {
        $query = Student::with(['section.category', 'kindergarten', 'parent']);

        // فلترة حسب البحث
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('father_name', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%")
                  ->orWhere('father_phone', 'like', "%{$search}%");
            });
        }

        // فلترة حسب الروضة
        if ($request->kindergarten_id) {
            $query->where('kindergarten_id', $request->kindergarten_id);
        }

        // فلترة حسب الحالة
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $students = $query->latest()->paginate(20);
        $kindergartens = Kindergarten::all();

        return view('students.index', compact('students', 'kindergartens'));
    }

    /**
     * عرض نموذج إضافة طالب
     */
    public function create()
    {
        $kindergartens = Kindergarten::where('is_active', true)->get();
        $categories = Category::with('sections')->get();
        
        return view('students.create', compact('kindergartens', 'categories'));
    }

    /**
     * حفظ طالب جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kindergarten_id' => 'required|exists:kindergartens,id',
            'section_id' => 'required|exists:sections,id',
            'first_name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'grandfather_name' => 'required|string|max:255',
            'family_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'birth_date' => 'required|date|before:today',
            'father_phone' => 'required|string|max:20',
            'academic_year' => 'required|string',
        ], [
            'kindergarten_id.required' => 'يرجى اختيار الروضة',
            'section_id.required' => 'يرجى اختيار الشعبة',
            'first_name.required' => 'اسم الطالب مطلوب',
            'father_name.required' => 'اسم الأب مطلوب',
            'birth_date.required' => 'تاريخ الميلاد مطلوب',
            'father_phone.required' => 'رقم جوال الأب مطلوب',
        ]);

        // توليد رقم قيد تلقائي
        $validated['registration_number'] = $this->generateRegistrationNumber();
        $validated['status'] = 'active';
        $validated['nationality'] = $request->nationality ?? 'ليبي';
        $validated['siblings_count'] = $request->siblings_count ?? 0;
        $validated['birth_order'] = $request->birth_order ?? 1;
        $validated['needs_transportation'] = $request->boolean('needs_transportation');
        $validated['needs_bathroom_care'] = $request->boolean('needs_bathroom_care');
        $validated['address'] = $request->address;
        $validated['mother_phone'] = $request->mother_phone;
        $validated['father_marital_status'] = $request->father_marital_status ?? 'متزوج';
        $validated['mother_marital_status'] = $request->mother_marital_status ?? 'متزوجة';

        // إنشاء الطالب
        $student = Student::create($validated);

        return redirect()->route('students.index')
            ->with('success', '✅ تم تسجيل الطالب بنجاح! رقم القيد: ' . $student->registration_number);
    }

    /**
     * عرض تفاصيل طالب
     */
    public function show(Student $student)
    {
        $student->load(['section.category', 'kindergarten', 'parent']);
        
        return view('students.show', compact('student'));
    }

    /**
     * عرض نموذج تعديل طالب
     */
    public function edit(Student $student)
    {
        $kindergartens = Kindergarten::where('is_active', true)->get();
        $categories = Category::with('sections')->get();
        
        return view('students.edit', compact('student', 'kindergartens', 'categories'));
    }

    /**
     * تحديث بيانات طالب
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'kindergarten_id' => 'required|exists:kindergartens,id',
            'section_id' => 'required|exists:sections,id',
            'first_name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive,graduated,withdrawn',
        ]);

        $student->update($validated);

        return redirect()->route('students.show', $student)
            ->with('success', '✅ تم تحديث بيانات الطالب بنجاح');
    }

    /**
     * حذف طالب
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', '✅ تم حذف الطالب بنجاح');
    }

    /**
     * توليد رقم قيد تلقائي
     */
    private function generateRegistrationNumber()
    {
        $year = date('Y');
        $lastNumber = Student::whereYear('created_at', $year)
            ->max('registration_number');

        if ($lastNumber) {
            $number = intval(substr($lastNumber, -4)) + 1;
        } else {
            $number = 1;
        }

        return $year . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}