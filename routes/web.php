<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeeStructureController;
use App\Http\Controllers\StudentFeeController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\SalaryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Student;
use App\Models\Category;
use App\Models\Kindergarten;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// اختبار سريع
Route::get('/test', function () {
    return 'Laravel يعمل بنجاح ✅';
});

// الصفحة الرئيسية
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

// صفحة تسجيل الدخول
Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('test-login');
})->name('login');

// صفحة دخول بديلة لأن النموذج السابق كان يستخدم /test-login
Route::get('/test-login', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('test-login');
})->name('test-login');

// معالجة تسجيل الدخول من /login
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        if (auth()->check()) {
            auth()->user()->update([
                'last_login_at' => now(),
            ]);
        }

        return redirect()->route('dashboard');
    }

    return back()->with('error', 'بيانات الدخول غير صحيحة')->withInput();
})->middleware('throttle:6,1')->name('login.submit');

// معالجة تسجيل الدخول من /test-login أيضاً
Route::post('/test-login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        if (auth()->check()) {
            auth()->user()->update([
                'last_login_at' => now(),
            ]);
        }

        return redirect()->route('dashboard');
    }

    return back()->with('error', 'بيانات الدخول غير صحيحة')->withInput();
})->middleware('throttle:6,1');

// تسجيل الخروج
Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login')->with('success', 'تم تسجيل الخروج بنجاح');
})->name('logout');


// كل ما بداخل هذه المجموعة يحتاج تسجيل دخول
Route::middleware('auth')->group(function () {

    // لوحة التحكم
    Route::get('/dashboard', function () {
        $stats = [
            'total_students' => Student::where('status', 'active')->count(),
            'total_teachers' => User::where('role', 'teacher')->where('is_active', true)->count(),
            'total_kindergartens' => Kindergarten::where('is_active', true)->count(),
            'total_parents' => User::where('role', 'parent')->count(),
        ];

        return view('dashboard', compact('stats'));
    })->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | إدارة الطلاب
    |--------------------------------------------------------------------------
    */

    // قائمة الطلاب
    Route::get('/students', function (Request $request) {
        $query = Student::with(['section.category', 'kindergarten']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('father_name', 'like', "%{$search}%")
                    ->orWhere('grandfather_name', 'like', "%{$search}%")
                    ->orWhere('family_name', 'like', "%{$search}%")
                    ->orWhere('registration_number', 'like', "%{$search}%")
                    ->orWhere('father_phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kindergarten_id')) {
            $query->where('kindergarten_id', $request->kindergarten_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $students = $query->latest()->paginate(20);
        $kindergartens = Kindergarten::all();

        return view('students.index', compact('students', 'kindergartens'));
    })->name('students.index');


    // صفحة إضافة طالب
    Route::get('/students/create', function () {
        $kindergartens = Kindergarten::where('is_active', true)->get();
        $categories = Category::with('sections')->get();

        return view('students.create', compact('kindergartens', 'categories'));
    })->name('students.create');


    // حفظ طالب جديد
    Route::post('/students', function (Request $request) {
        $validated = $request->validate([
            'kindergarten_id' => 'required|exists:kindergartens,id',
            'section_id' => 'required|exists:sections,id',
            'first_name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'grandfather_name' => 'required|string|max:255',
            'family_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'father_phone' => 'required|string|max:20',
            'academic_year' => 'required|string|max:20',
        ], [
            'kindergarten_id.required' => 'يرجى اختيار الروضة',
            'section_id.required' => 'يرجى اختيار الشعبة',
            'first_name.required' => 'اسم الطالب مطلوب',
            'father_name.required' => 'اسم الأب مطلوب',
            'grandfather_name.required' => 'اسم الجد مطلوب',
            'family_name.required' => 'اسم العائلة مطلوب',
            'mother_name.required' => 'اسم الأم مطلوب',
            'birth_date.required' => 'تاريخ الميلاد مطلوب',
            'father_phone.required' => 'رقم جوال الأب مطلوب',
            'academic_year.required' => 'السنة الدراسية مطلوبة',
        ]);

        /*
         | توليد رقم قيد آمن لا يتكرر
         | حتى لو كان هناك طلاب محذوفون Soft Delete
         */
        $year = now()->format('Y');

        $lastRegistrationNumber = Student::withTrashed()
            ->where('registration_number', 'like', $year . '%')
            ->orderByRaw('CAST(SUBSTRING(registration_number, 5) AS UNSIGNED) DESC')
            ->value('registration_number');

        $nextNumber = $lastRegistrationNumber
            ? ((int) substr($lastRegistrationNumber, 4)) + 1
            : 1;

        do {
            $registrationNumber = $year . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            $exists = Student::withTrashed()
                ->where('registration_number', $registrationNumber)
                ->exists();

            $nextNumber++;
        } while ($exists);

        $validated['registration_number'] = $registrationNumber;

        // القيم الاختيارية
        $validated['status'] = 'active';
        $validated['nationality'] = $request->nationality ?? 'ليبي';
        $validated['siblings_count'] = $request->siblings_count ?? 0;
        $validated['birth_order'] = $request->birth_order ?? 1;
        $validated['address'] = $request->address;
        $validated['mother_phone'] = $request->mother_phone;
        $validated['father_marital_status'] = $request->father_marital_status ?? 'متزوج';
        $validated['mother_marital_status'] = $request->mother_marital_status ?? 'متزوجة';
        $validated['needs_transportation'] = $request->boolean('needs_transportation');
        $validated['needs_bathroom_care'] = $request->boolean('needs_bathroom_care');

        Student::create($validated);

        return redirect()
            ->route('students.index')
            ->with('success', '✅ تم تسجيل الطالب بنجاح، رقم القيد: ' . $registrationNumber);
    })->name('students.store');


    // صفحة تعديل الطالب
    // مهم أن تأتي قبل /students/{student}
    Route::get('/students/{student}/edit', function (Student $student) {
        $kindergartens = Kindergarten::where('is_active', true)->get();
        $categories = Category::with('sections')->get();

        return view('students.edit', compact('student', 'kindergartens', 'categories'));
    })->name('students.edit');


    // تحديث بيانات الطالب
    Route::put('/students/{student}', function (Request $request, Student $student) {
        $validated = $request->validate([
            'kindergarten_id' => 'required|exists:kindergartens,id',
            'section_id' => 'required|exists:sections,id',
            'first_name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'grandfather_name' => 'required|string|max:255',
            'family_name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive,graduated,withdrawn',
        ]);

        $student->update($validated);

        return redirect()
            ->route('students.show', $student)
            ->with('success', '✅ تم تحديث بيانات الطالب بنجاح');
    })->name('students.update');


    // عرض تفاصيل الطالب
    Route::get('/students/{student}', function (Student $student) {
        $student->load(['section.category', 'kindergarten', 'parent']);

        return view('students.show', compact('student'));
    })->name('students.show');


    // حذف الطالب
    Route::delete('/students/{student}', function (Student $student) {
        $student->delete();

        return redirect()
            ->route('students.index')
            ->with('success', '✅ تم حذف الطالب بنجاح');
    })->name('students.destroy');
});


Route::middleware(['auth','role:super_admin,admin'])->group(function () {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
});

// موارد المحاسبة محفوظة للمديرين والمحاسبين
Route::middleware(['auth','role:super_admin,admin,accountant'])->group(function () {
    Route::resource('fee-structures', FeeStructureController::class);
    Route::resource('student-fees', StudentFeeController::class);
    Route::resource('installments', InstallmentController::class);
    Route::resource('expenses', ExpenseController::class);
    Route::resource('salaries', SalaryController::class);

    // Reports
    Route::get('reports/monthly', [\App\Http\Controllers\ReportsController::class, 'monthly'])->name('reports.monthly');
    Route::get('reports/student/{student}', [\App\Http\Controllers\ReportsController::class, 'studentBalance'])->name('reports.student');
});

