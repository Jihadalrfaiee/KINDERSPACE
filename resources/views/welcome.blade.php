<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة رياض الأطفال</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Tajawal', sans-serif; }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center">
    
    <!-- خلفية زخرفية -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 right-20 text-white/10 text-9xl floating">🎈</div>
        <div class="absolute bottom-20 left-20 text-white/10 text-9xl floating" style="animation-delay: 1s;">🎨</div>
        <div class="absolute top-40 left-1/3 text-white/10 text-7xl floating" style="animation-delay: 0.5s;">⭐</div>
        <div class="absolute bottom-40 right-1/3 text-white/10 text-7xl floating" style="animation-delay: 1.5s;">🌈</div>
    </div>

    <!-- المحتوى الرئيسي -->
    <div class="relative z-10 text-center text-white px-4">
        
        <!-- الشعار -->
        <div class="mb-8">
            <div class="w-32 h-32 bg-white/20 backdrop-blur-lg rounded-3xl mx-auto flex items-center justify-center mb-6 shadow-2xl floating">
                <span class="text-7xl">🏫</span>
            </div>
            <h1 class="text-5xl md:text-6xl font-bold mb-4">
                نظام إدارة رياض الأطفال
            </h1>
            <p class="text-xl md:text-2xl text-white/90 mb-2">
                روضة الزهراء النموذجية | فضاء الأطفال النموذجية
            </p>
            <p class="text-lg text-white/70">
                نظام شامل ومتكامل لإدارة رياض الأطفال
            </p>
        </div>

        <!-- إحصائيات سريعة -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10 max-w-4xl mx-auto">
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
                <div class="text-4xl mb-2">👶</div>
                <h3 class="text-3xl font-bold">0</h3>
                <p class="text-sm text-white/80">طالب مسجل</p>
            </div>
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
                <div class="text-4xl mb-2">👥</div>
                <h3 class="text-3xl font-bold">0</h3>
                <p class="text-sm text-white/80">موظف</p>
            </div>
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
                <div class="text-4xl mb-2">📚</div>
                <h3 class="text-3xl font-bold">5</h3>
                <p class="text-sm text-white/80">مواد دراسية</p>
            </div>
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
                <div class="text-4xl mb-2">🏛️</div>
                <h3 class="text-3xl font-bold">2</h3>
                <p class="text-sm text-white/80">روضة نموذجية</p>
            </div>
        </div>

        <!-- أزرار الدخول -->
        <div class="flex flex-col md:flex-row gap-4 justify-center items-center mb-8">
            <a href="/login" class="group relative inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-purple-600 bg-white rounded-full hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-purple-500/50">
                <span class="mr-3">🔑</span>
                تسجيل الدخول
                <svg class="w-5 h-5 mr-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            
            <a href="/about" class="group inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white bg-white/20 backdrop-blur-lg border-2 border-white/30 rounded-full hover:bg-white/30 transition-all duration-300">
                <span class="mr-3">ℹ️</span>
                عن النظام
            </a>
        </div>

        <!-- المميزات -->
        <div class="max-w-4xl mx-auto">
            <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-8 border border-white/20">
                <h2 class="text-2xl font-bold mb-6">✨ المميزات الرئيسية</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-right">
                    <div class="p-4">
                        <div class="text-3xl mb-3">📊</div>
                        <h3 class="font-bold text-lg mb-2">إدارة شاملة</h3>
                        <p class="text-sm text-white/80">إدارة الطلاب، المعلمات، والمحاسبة من مكان واحد</p>
                    </div>
                    <div class="p-4">
                        <div class="text-3xl mb-3">📱</div>
                        <h3 class="font-bold text-lg mb-2">إشعارات تلقائية</h3>
                        <p class="text-sm text-white/80">إشعارات فورية لأولياء الأمور عبر تيليغرام</p>
                    </div>
                    <div class="p-4">
                        <div class="text-3xl mb-3">💰</div>
                        <h3 class="font-bold text-lg mb-2">نظام مالي متكامل</h3>
                        <p class="text-sm text-white/80">متابعة الرسوم والأقساط بسهولة</p>
                    </div>
                    <div class="p-4">
                        <div class="text-3xl mb-3">⭐</div>
                        <h3 class="font-bold text-lg mb-2">تقييم دوري</h3>
                        <p class="text-sm text-white/80">تقييم أسبوعي لأداء الطلاب في جميع المواد</p>
                    </div>
                    <div class="p-4">
                        <div class="text-3xl mb-3">📈</div>
                        <h3 class="font-bold text-lg mb-2">تقارير تفصيلية</h3>
                        <p class="text-sm text-white/80">تقارير شاملة عن الحضور والأداء والمالية</p>
                    </div>
                    <div class="p-4">
                        <div class="text-3xl mb-3">🔒</div>
                        <h3 class="font-bold text-lg mb-2">آمن ومحمي</h3>
                        <p class="text-sm text-white/80">نظام صلاحيات متقدم ونسخ احتياطي تلقائي</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- معلومات التواصل -->
        <div class="mt-10 text-white/70 text-sm">
            <p>© 2024 نظام إدارة رياض الأطفال - جميع الحقوق محفوظة</p>
            <p class="mt-2">صُنع بـ ❤️ في ليبيا</p>
        </div>

    </div>

</body>
</html>