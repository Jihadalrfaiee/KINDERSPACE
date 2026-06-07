<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - نظام إدارة رياض الأطفال</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { font-family: 'Tajawal', sans-serif; }
        .login-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }
    </style>
</head>
<body class="login-bg min-h-screen flex items-center justify-center p-4">

    <!-- خلفية زخرفية -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 right-20 text-white/10 text-9xl floating">🎈</div>
        <div class="absolute bottom-20 left-20 text-white/10 text-9xl floating" style="animation-delay: 1s;">🎨</div>
        <div class="absolute top-40 left-1/3 text-white/10 text-7xl floating" style="animation-delay: 0.5s;">⭐</div>
        <div class="absolute bottom-40 right-1/3 text-white/10 text-7xl floating" style="animation-delay: 1.5s;">🌈</div>
    </div>

    <div class="relative w-full max-w-md">
        <!-- بطاقة تسجيل الدخول -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <!-- الشعار -->
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-gradient-to-r from-purple-500 to-blue-500 rounded-2xl mx-auto flex items-center justify-center mb-4 shadow-lg">
                    <span class="text-4xl">🏫</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">نظام إدارة رياض الأطفال</h1>
                <p class="text-gray-500 mt-1">مرحباً بك! سجّل دخولك للمتابعة</p>
            </div>

            <!-- رسائل الخطأ -->
            @if($errors->any())
            <div class="bg-red-50 border-r-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
                <div class="flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- نموذج الدخول -->
            <form action="{{ route('login.submit') }}" method="POST" class="space-y-5">
                @csrf

                <!-- البريد الإلكتروني -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-envelope ml-1 text-purple-500"></i>
                        البريد الإلكتروني
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:outline-none transition"
                           placeholder="أدخل البريد الإلكتروني">
                </div>

                <!-- كلمة المرور -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-lock ml-1 text-purple-500"></i>
                        كلمة المرور
                    </label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:outline-none transition"
                           placeholder="أدخل كلمة المرور">
                </div>

                <!-- تذكرني -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-purple-500 rounded">
                        <span class="text-sm text-gray-600">تذكرني</span>
                    </label>
                </div>

                <!-- زر الدخول -->
                <button type="submit"
                        class="w-full py-3 bg-gradient-to-r from-purple-600 to-blue-500 text-white font-bold rounded-xl hover:opacity-90 transition shadow-lg">
                    <i class="fas fa-sign-in-alt ml-2"></i>
                    تسجيل الدخول
                </button>
            </form>

            <!-- رابط العودة -->
            <div class="mt-6 text-center">
                <a href="/" class="text-sm text-gray-600 hover:text-purple-600 transition">
                    <i class="fas fa-arrow-right ml-1"></i>
                    العودة للصفحة الرئيسية
                </a>
            </div>
        </div>

        <!-- حقوق النشر -->
        <p class="text-center text-white/60 text-sm mt-6">
            © 2024 نظام إدارة رياض الأطفال - جميع الحقوق محفوظة
        </p>
    </div>

</body>
</html>