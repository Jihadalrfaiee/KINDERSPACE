<details> <summary><!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل بيانات الطالب</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-100">

    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-4">
                    <a href="/dashboard" class="text-2xl">🏫</a>
                    <h1 class="text-xl font-bold text-gray-800">تعديل بيانات الطالب</h1>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('students.show', $student) }}" class="text-blue-500 hover:text-blue-700">
                        <i class="fas fa-arrow-right"></i> العودة
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto py-6 px-4">

        <form action="{{ route('students.update', $student) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-3">
                    <i class="fas fa-school text-blue-500"></i> معلومات الروضة والشعبة
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            الروضة <span class="text-red-500">*</span>
                        </label>
                        <select name="kindergarten_id" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @foreach($kindergartens as $kg)
                            <option value="{{ $kg->id }}" {{ $student->kindergarten_id == $kg->id ? 'selected' : '' }}>
                                {{ $kg->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">الشعبة</label>
                        <select name="section_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @foreach($categories as $cat)
                                @foreach($cat->sections as $sec)
                                <option value="{{ $sec->id }}" {{ $student->section_id == $sec->id ? 'selected' : '' }}>
                                    {{ $cat->name }} - {{ $sec->name }}
                                </option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">الحالة</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            <option value="active" {{ $student->status == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ $student->status == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-3">
                    <i class="fas fa-child text-purple-500"></i> بيانات الطالب
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">اسم الطالب</label>
                        <input type="text" name="first_name" value="{{ $student->first_name }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">اسم الأب</label>
                        <input type="text" name="father_name" value="{{ $student->father_name }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">اسم الجد</label>
                        <input type="text" name="grandfather_name" value="{{ $student->grandfather_name }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">اسم العائلة</label>
                        <input type="text" name="family_name" value="{{ $student->family_name }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <a href="{{ route('students.show', $student) }}" 
                       class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200">
                        <i class="fas fa-times mr-2"></i> إلغاء
                    </a>

                    <button type="submit" 
                            class="bg-gradient-to-r from-green-500 to-blue-500 text-white px-8 py-3 rounded-lg hover:opacity-90 font-bold">
                        <i class="fas fa-save mr-2"></i> حفظ التعديلات
                    </button>
                </div>
            </div>

        </form>

    </div>

</body>
</html></summary>