<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * عرض قائمة المستخدمين
     * الصلاحية المطلوبة: users.view
     */
    public function index()
    {
        // التحقق من الصلاحية
        if (!auth()->user()->hasPermission('users.view')) {
            abort(403, 'ليس لديك صلاحية لعرض المستخدمين');
        }

        // جلب المستخدمين مع أدوارهم
        $users = User::with('role')
            ->latest()
            ->paginate(15);

        return view('users.index', compact('users'));
    }

    /**
     * عرض نموذج إضافة مستخدم جديد
     * الصلاحية المطلوبة: users.create
     */
    public function create()
    {
        if (!auth()->user()->hasPermission('users.create')) {
            abort(403, 'ليس لديك صلاحية لإضافة مستخدمين');
        }

        // جلب الأدوار النشطة فقط
        $roles = Role::where('is_active', true)
            ->orderBy('display_name')
            ->get();

        return view('users.create', compact('roles'));
    }

    /**
     * حفظ مستخدم جديد
     * الصلاحية المطلوبة: users.create
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('users.create')) {
            abort(403, 'ليس لديك صلاحية لإضافة مستخدمين');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يجب أن يكون البريد الإلكتروني صالحاً',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed' => 'كلمة المرور غير متطابقة',
            'role_id.required' => 'يجب اختيار دور للمستخدم',
            'role_id.exists' => 'الدور المحدد غير صالح',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'تم إضافة المستخدم "' . $user->name . '" بنجاح');
    }

    /**
     * عرض تفاصيل مستخدم
     * الصلاحية المطلوبة: users.view
     */
    public function show(User $user)
    {
        if (!auth()->user()->hasPermission('users.view')) {
            abort(403, 'ليس لديك صلاحية لعرض المستخدمين');
        }

        $user->load('role');

        return view('users.show', compact('user'));
    }

    /**
     * عرض نموذج تعديل مستخدم
     * الصلاحية المطلوبة: users.edit
     */
    public function edit(User $user)
    {
        if (!auth()->user()->hasPermission('users.edit')) {
            abort(403, 'ليس لديك صلاحية لتعديل المستخدمين');
        }

        // جلب الأدوار النشطة فقط
        $roles = Role::where('is_active', true)
            ->orderBy('display_name')
            ->get();

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * تحديث بيانات مستخدم
     * الصلاحية المطلوبة: users.edit
     */
    public function update(Request $request, User $user)
    {
        if (!auth()->user()->hasPermission('users.edit')) {
            abort(403, 'ليس لديك صلاحية لتعديل المستخدمين');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يجب أن يكون البريد الإلكتروني صالحاً',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed' => 'كلمة المرور غير متطابقة',
            'role_id.required' => 'يجب اختيار دور للمستخدم',
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role_id' => $validated['role_id'],
        ];

        // تحديث كلمة المرور فقط إذا تم إدخالها
        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);

        return redirect()
            ->route('users.index')
            ->with('success', 'تم تحديث بيانات المستخدم "' . $user->name . '" بنجاح');
    }

    /**
     * حذف مستخدم
     * الصلاحية المطلوبة: users.delete
     */
    public function destroy(User $user)
    {
        if (!auth()->user()->hasPermission('users.delete')) {
            abort(403, 'ليس لديك صلاحية لحذف المستخدمين');
        }

        // منع حذف الحساب الحالي
        if ($user->id === auth()->id()) {
            return back()->with('error', 'لا يمكنك حذف حسابك الحالي!');
        }

        // منع حذف حساب المدير العام (اختياري - للحماية)
        if ($user->hasRole('admin')) {
            return back()->with('error', 'لا يمكن حذف حساب المدير العام!');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'تم حذف المستخدم "' . $userName . '" بنجاح');
    }
}