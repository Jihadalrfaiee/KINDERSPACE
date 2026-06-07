<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - نظام إدارة رياض الأطفال</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    </style>
</head>
<body class="bg-gray-100">

    <!-- الشريط العلوي -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="text-2xl">🏫</span>
                    <h1 class="mr-3 text-xl font-bold text-gray-800">نظام إدارة رياض الأطفال</h1>
                </div>
                
                <div class="flex items-center gap-4">
                    <span class="text-gray-700">مرحباً، <strong>{{ auth()->user()->name }}</strong></span>
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

    <!-- المحتوى الرئيسي -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        
        <!-- رسالة الترحيب -->
        <div class="card-gradient text-white rounded-2xl p-8 mb-6 shadow-xl">
            <h2 class="text-3xl font-bold mb-2">مرحباً بك! 👋</h2>
            <p class="text-lg opacity-90">أهلاً بك في نظام إدارة رياض الأطفال المتكامل</p>
        </div>

        <!-- بطاقات الإحصائيات -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            
            <!-- إجمالي الطلاب -->
            <div class="bg-white rounded-xl shadow-md p-6 border-t-4 border-blue-500 hover:shadow-xl transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">إجمالي الطلاب</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_students'] }}</h3>
                    </div>
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-child text-2xl text-blue-500"></i>
                    </div>
                </div>
            </div>

            <!-- المعلمات -->
            <div class="bg-white rounded-xl shadow-md p-6 border-t-4 border-green-500 hover:shadow-xl transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">المعلمات</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_teachers'] }}</h3>
                    </div>
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chalkboard-teacher text-2xl text-green-500"></i>
                    </div>
                </div>
            </div>

            <!-- الروضات -->
            <div class="bg-white rounded-xl shadow-md p-6 border-t-4 border-purple-500 hover:shadow-xl transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">الروضات</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_kindergartens'] }}</h3>
                    </div>
                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-school text-2xl text-purple-500"></i>
                    </div>
                </div>
            </div>

            <!-- أولياء الأمور -->
            <div class="bg-white rounded-xl shadow-md p-6 border-t-4 border-yellow-500 hover:shadow-xl transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">أولياء الأمور</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_parents'] }}</h3>
                    </div>
                    <div class="w-14 h-14 bg-yellow-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-friends text-2xl text-yellow-500"></i>
                    </div>
                </div>
            </div>

        </div>
 <!-- قوائم الوصول السريع -->

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <a href="{{ route('students.create') }}" class="flex flex-col items-center p-6 bg-blue-50 rounded-xl hover:bg-blue-100 transition">
        <i class="fas fa-user-plus text-3xl text-blue-500 mb-3"></i>
        <span class="font-bold text-gray-700">إضافة طالب</span>
    </a>

    <a href="{{ route('students.index') }}" class="flex flex-col items-center p-6 bg-green-50 rounded-xl hover:bg-green-100 transition">
        <i class="fas fa-users text-3xl text-green-500 mb-3"></i>
        <span class="font-bold text-gray-700">قائمة الطلاب</span>
    </a>

    <a href="#" class="flex flex-col items-center p-6 bg-purple-50 rounded-xl hover:bg-purple-100 transition">
        <i class="fas fa-star text-3xl text-purple-500 mb-3"></i>
        <span class="font-bold text-gray-700">التقييمات</span>
    </a>

    <a href="#" class="flex flex-col items-center p-6 bg-yellow-50 rounded-xl hover:bg-yellow-100 transition">
        <i class="fas fa-chart-bar text-3xl text-yellow-500 mb-3"></i>
        <span class="font-bold text-gray-700">التقارير</span>
    </a>
</div>

    </div>

</body>
</html>