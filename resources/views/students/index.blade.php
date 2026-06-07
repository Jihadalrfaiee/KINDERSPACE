<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الطلاب</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-100">

    <!-- الشريط العلوي -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-4">
                    <a href="/dashboard" class="text-2xl">🏫</a>
                    <h1 class="text-xl font-bold text-gray-800">إدارة الطلاب</h1>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="/dashboard" class="text-blue-500 hover:text-blue-700">
                        <i class="fas fa-home"></i> الرئيسية
                    </a>
                    <span class="text-gray-700">{{ auth()->user()->name }}</span>
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
        <i class="fas fa-sign-out-alt"></i> خروج
    </button>
</form>

                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 px-4">

        <!-- رسائل النجاح -->
        @if(session('success'))
        <div class="bg-green-100 border-r-4 border-green-500 text-green-700 p-4 rounded-lg mb-4">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        <!-- شريط الأدوات -->
        <div class="bg-white rounded-xl shadow-md p-4 mb-6">
            <div class="flex items-center justify-between flex-wrap gap-4 mb-4">
                <a href="{{ route('students.create') }}" 
                   class="bg-gradient-to-r from-blue-500 to-purple-500 text-white px-6 py-3 rounded-lg hover:opacity-90 transition font-bold">
                    <i class="fas fa-plus"></i> تسجيل طالب جديد
                </a>
                
                <div class="text-sm text-gray-600">
                    إجمالي الطلاب: <span class="font-bold text-blue-600">{{ $students->total() }}</span>
                </div>
            </div>

            <!-- فلاتر البحث -->
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="🔍 بحث (الاسم، رقم القيد، الجوال...)"
                       class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">

                <select name="kindergarten_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">كل الروضات</option>
                    @foreach($kindergartens as $kg)
                    <option value="{{ $kg->id }}" {{ request('kindergarten_id') == $kg->id ? 'selected' : '' }}>
                        {{ $kg->name }}
                    </option>
                    @endforeach
                </select>

                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">كل الحالات</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                </select>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                    <i class="fas fa-search"></i> بحث
                </button>
            </form>
        </div>

        <!-- جدول الطلاب -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-700">#</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-700">رقم القيد</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-700">الاسم الكامل</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-700">الروضة</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-700">الفئة/الشعبة</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-700">جوال الأب</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-700">الحالة</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-700">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($students as $student)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-sm">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3">
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">
                                    {{ $student->registration_number }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-r from-pink-400 to-purple-400 rounded-full flex items-center justify-center">
                                        <span class="text-white font-bold">{{ mb_substr($student->first_name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800">{{ $student->full_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $student->age }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $student->kindergarten->name ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs">
                                    {{ $student->section->category->name ?? '' }} - {{ $student->section->name ?? '' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm font-mono">{{ $student->father_phone }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($student->status === 'active')
                                    <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-bold">
                                        <i class="fas fa-check-circle"></i> نشط
                                    </span>
                                @else
                                    <span class="bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded-full">غير نشط</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('students.show', $student) }}" 
                                       class="text-blue-600 hover:text-blue-800" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('students.edit', $student) }}" 
                                       class="text-yellow-600 hover:text-yellow-800" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('students.destroy', $student) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذا الطالب؟')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-2 text-gray-500">
                                    <i class="fas fa-inbox text-6xl text-gray-300"></i>
                                    <p class="text-lg font-bold">لا توجد نتائج</p>
                                    <p class="text-sm">لم يتم تسجيل أي طالب بعد</p>
                                    <a href="{{ route('students.create') }}" 
                                       class="mt-4 bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                                        <i class="fas fa-plus"></i> تسجيل طالب جديد
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($students->hasPages())
            <div class="px-4 py-3 border-t">
                {{ $students->links() }}
            </div>
            @endif
        </div>

    </div>

</body>
</html>