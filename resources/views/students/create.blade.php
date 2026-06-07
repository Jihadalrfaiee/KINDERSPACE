<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل طالب جديد</title>
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
                    <h1 class="text-xl font-bold text-gray-800">تسجيل طالب جديد</h1>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="{{ route('students.index') }}" class="text-blue-500 hover:text-blue-700">
                        <i class="fas fa-list"></i> قائمة الطلاب
                    </a>
                    <a href="/dashboard" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-home"></i> الرئيسية
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto py-6 px-4">

        <form action="{{ route('students.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- معلومات الروضة والشعبة -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2 border-b pb-3">
                    <i class="fas fa-school text-blue-500"></i>
                    معلومات الروضة والشعبة
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            الروضة <span class="text-red-500">*</span>
                        </label>
                        <select name="kindergarten_id" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="">اختر الروضة</option>
                            @foreach($kindergartens as $kg)
                            <option value="{{ $kg->id }}">{{ $kg->name }}</option>
                            @endforeach
                        </select>
                        @error('kindergarten_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            الفئة <span class="text-red-500">*</span>
                        </label>
                        <select id="category_select" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                onchange="filterSections(this.value)">
                            <option value="">اختر الفئة</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            الشعبة <span class="text-red-500">*</span>
                        </label>
                        <select name="section_id" id="section_select" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="">اختر الشعبة</option>
                            @foreach($categories as $cat)
                                @foreach($cat->sections as $sec)
                                <option value="{{ $sec->id }}" data-category="{{ $cat->id }}" style="display:none;">
                                    {{ $sec->name }}
                                </option>
                                @endforeach
                            @endforeach
                        </select>
                        @error('section_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- بيانات الطالب -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2 border-b pb-3">
                    <i class="fas fa-child text-purple-500"></i>
                    بيانات الطالب
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            اسم الطالب <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @error('first_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            اسم الأب <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="father_name" value="{{ old('father_name') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @error('father_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            اسم الجد <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="grandfather_name" value="{{ old('grandfather_name') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @error('grandfather_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            اسم العائلة <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="family_name" value="{{ old('family_name') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @error('family_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            اسم الأم <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="mother_name" value="{{ old('mother_name') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @error('mother_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            تاريخ الميلاد <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="birth_date" value="{{ old('birth_date') }}" required max="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @error('birth_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">الجنسية</label>
                        <input type="text" name="nationality" value="{{ old('nationality', 'ليبي') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            السنة الدراسية <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="academic_year" value="{{ old('academic_year', '2024-2025') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">عدد الإخوة</label>
                        <input type="number" name="siblings_count" value="{{ old('siblings_count', 0) }}" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">الترتيب بين الإخوة</label>
                        <input type="number" name="birth_order" value="{{ old('birth_order', 1) }}" min="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">مكان السكن</label>
                        <input type="text" name="address" value="{{ old('address') }}"
                               placeholder="المدينة - المنطقة - الشارع"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- بيانات الوالدين -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2 border-b pb-3">
                    <i class="fas fa-users text-green-500"></i>
                    بيانات الوالدين
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            الحالة الاجتماعية للأب <span class="text-red-500">*</span>
                        </label>
                        <select name="father_marital_status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="متزوج">متزوج</option>
                            <option value="مطلق">مطلق</option>
                            <option value="أرمل">أرمل</option>
                            <option value="منفصل">منفصل</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            الحالة الاجتماعية للأم <span class="text-red-500">*</span>
                        </label>
                        <select name="mother_marital_status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="متزوجة">متزوجة</option>
                            <option value="مطلقة">مطلقة</option>
                            <option value="أرملة">أرملة</option>
                            <option value="منفصلة">منفصلة</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            رقم جوال الأب <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="father_phone" value="{{ old('father_phone') }}" required
                               placeholder="09XXXXXXXX"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @error('father_phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">رقم جوال الأم</label>
                        <input type="tel" name="mother_phone" value="{{ old('mother_phone') }}"
                               placeholder="09XXXXXXXX"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- احتياجات خاصة -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2 border-b pb-3">
                    <i class="fas fa-heart text-red-500"></i>
                    احتياجات خاصة
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center gap-3 p-4 bg-blue-50 rounded-lg">
                        <input type="checkbox" name="needs_transportation" value="1" id="needs_transportation"
                               class="w-5 h-5 text-blue-600 rounded">
                        <label for="needs_transportation" class="font-bold text-gray-700 cursor-pointer">
                            <i class="fas fa-bus text-blue-500"></i> يحتاج لخدمة المواصلات
                        </label>
                    </div>

                    <div class="flex items-center gap-3 p-4 bg-purple-50 rounded-lg">
                        <input type="checkbox" name="needs_bathroom_care" value="1" id="needs_bathroom_care"
                               class="w-5 h-5 text-purple-600 rounded">
                        <label for="needs_bathroom_care" class="font-bold text-gray-700 cursor-pointer">
                            <i class="fas fa-hands-helping text-purple-500"></i> يحتاج لرعاية دورة المياه
                        </label>
                    </div>
                </div>
            </div>

            <!-- أزرار الإجراءات -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <a href="{{ route('students.index') }}" 
                       class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200 transition">
                        <i class="fas fa-arrow-right mr-2"></i> إلغاء
                    </a>

                    <button type="submit" 
                            class="bg-gradient-to-r from-green-500 to-blue-500 text-white px-8 py-3 rounded-lg hover:opacity-90 transition font-bold">
                        <i class="fas fa-save mr-2"></i> حفظ البيانات وتسجيل الطالب
                    </button>
                </div>
            </div>

        </form>

    </div>

    <script>
    // فلترة الشعب حسب الفئة
    function filterSections(categoryId) {
        const sectionSelect = document.getElementById('section_select');
        const options = sectionSelect.querySelectorAll('option');
        
        options.forEach(option => {
            if (option.value === '') {
                option.style.display = 'block';
            } else if (categoryId === '' || option.dataset.category === categoryId) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
        
        sectionSelect.value = '';
    }
    </script>

</body>
</html>