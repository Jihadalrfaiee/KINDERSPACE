<details> <summary><!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الطالب</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-100">

    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-4">
                    <a href="/dashboard" class="text-2xl">🏫</a>
                    <h1 class="text-xl font-bold text-gray-800">تفاصيل الطالب</h1>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('students.index') }}" class="text-blue-500 hover:text-blue-700">
                        <i class="fas fa-arrow-right"></i> العودة
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto py-6 px-4">

        @if(session('success'))
        <div class="bg-green-100 border-r-4 border-green-500 text-green-700 p-4 rounded-lg mb-4">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        <div class="bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-xl shadow-lg p-8 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center border-4 border-white/30">
                        <span class="text-5xl">👶</span>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold mb-2">{{ $student->full_name }}</h2>
                        <div class="flex items-center gap-4 text-sm">
                            <span class="bg-white/20 px-3 py-1 rounded-full">
                                <i class="fas fa-id-card"></i> {{ $student->registration_number }}
                            </span>
                            <span class="bg-white/20 px-3 py-1 rounded-full">
                                <i class="fas fa-birthday-cake"></i> {{ $student->age }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="flex gap-2">
                    <a href="{{ route('students.edit', $student) }}" 
                       class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition">
                        <i class="fas fa-edit"></i> تعديل
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-3">
                <i class="fas fa-user text-blue-500"></i> المعلومات الشخصية
            </h3>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-xs text-gray-500">اسم الطالب</p>
                    <p class="font-bold text-gray-800">{{ $student->first_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">اسم الأب</p>
                    <p class="font-bold text-gray-800">{{ $student->father_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">اسم الجد</p>
                    <p class="font-bold text-gray-800">{{ $student->grandfather_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">اسم العائلة</p>
                    <p class="font-bold text-gray-800">{{ $student->family_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">اسم الأم</p>
                    <p class="font-bold text-gray-800">{{ $student->mother_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">تاريخ الميلاد</p>
                    <p class="font-bold text-gray-800">{{ $student->birth_date->format('Y-m-d') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">رقم جوال الأب</p>
                    <p class="font-bold text-gray-800">{{ $student->father_phone }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">الروضة</p>
                    <p class="font-bold text-gray-800">{{ $student->kindergarten->name ?? '-' }}</p>
                </div>
            </div>
        </div>

    </div>

</body>
</html></summary>