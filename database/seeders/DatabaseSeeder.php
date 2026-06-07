<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Kindergarten, User, Category, Section, Subject, BreakTime};
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        echo "🚀 بدء إدخال البيانات الأولية...\n\n";

        // ===== 1. إنشاء الروضتين =====
        echo "📍 إنشاء الروضات...\n";
        
        $zahra = Kindergarten::create([
            'name' => 'روضة الزهراء النموذجية',
            'address' => 'دمشق-سورية',
            'phone' => '0930352410',
            'email' => 'zahra@kindergarten.ly',
            'is_active' => true,
        ]);

        $fadaa = Kindergarten::create([
            'name' => 'فضاء الأطفال النموذجية',
            'address' => 'ببيلا - جانب بروستد القصور',
            'phone' => '0930352410',
            'email' => 'fadaa@kindergarten.ly',
            'is_active' => true,
        ]);

        echo "✅ تم إنشاء روضتين\n\n";

        // ===== 2. إنشاء المستخدمين =====
        echo "👤 إنشاء المستخدمين...\n";

        // المدير العام
        $superAdmin = User::create([
            'name' => 'المدير العام',
            'email' => 'admin@kindergarten.ly',
            'password' => Hash::make('password123'),
            'phone' => '0911111111',
            'role' => 'super_admin',
            'is_active' => true,
        ]);
        echo "  ✅ المدير العام: admin@kindergarten.ly / password123\n";

        // مديرة روضة الزهراء
        User::create([
            'kindergarten_id' => $zahra->id,
            'name' => 'مديرة روضة الزهراء',
            'email' => 'zahra.admin@kindergarten.ly',
            'password' => Hash::make('password123'),
            'phone' => '0912222222',
            'role' => 'admin',
            'is_active' => true,
        ]);

        // مديرة فضاء الأطفال
        User::create([
            'kindergarten_id' => $fadaa->id,
            'name' => 'مديرة فضاء الأطفال',
            'email' => 'fadaa.admin@kindergarten.ly',
            'password' => Hash::make('password123'),
            'phone' => '0913333333',
            'role' => 'admin',
            'is_active' => true,
        ]);

        // معلمات روضة الزهراء
        User::create([
            'kindergarten_id' => $zahra->id,
            'name' => 'فاطمة أحمد',
            'email' => 'fatima@kindergarten.ly',
            'password' => Hash::make('password123'),
            'phone' => '0914444444',
            'role' => 'teacher',
            'academic_qualification' => 'بكالوريوس تربية',
            'years_of_experience' => 5,
            'job_title' => 'معلمة لغة عربية',
            'is_active' => true,
        ]);

        User::create([
            'kindergarten_id' => $zahra->id,
            'name' => 'مريم سالم',
            'email' => 'mariam@kindergarten.ly',
            'password' => Hash::make('password123'),
            'phone' => '0915555555',
            'role' => 'teacher',
            'academic_qualification' => 'بكالوريوس لغة إنجليزية',
            'years_of_experience' => 3,
            'job_title' => 'معلمة لغة إنجليزية',
            'is_active' => true,
        ]);

        // محاسبة روضة الزهراء
        User::create([
            'kindergarten_id' => $zahra->id,
            'name' => 'عائشة محمد',
            'email' => 'accountant@kindergarten.ly',
            'password' => Hash::make('password123'),
            'phone' => '0916666666',
            'role' => 'accountant',
            'is_active' => true,
        ]);

        // ولي أمر تجريبي
        $parent = User::create([
            'kindergarten_id' => $zahra->id,
            'name' => 'أحمد علي (ولي أمر)',
            'email' => 'parent@kindergarten.ly',
            'password' => Hash::make('password123'),
            'phone' => '0917777777',
            'role' => 'parent',
            'is_active' => true,
        ]);

        echo "✅ تم إنشاء 7 مستخدمين\n\n";

        // ===== 3. إنشاء الفئات والشعب =====
        echo "📚 إنشاء الفئات والشعب...\n";

        $categoryNames = ['الفئة الأولى', 'الفئة الثانية', 'الفئة الثالثة'];
        $sectionNames = ['شعبة أ', 'شعبة ب', 'شعبة ج', 'شعبة د'];

        foreach ([$zahra, $fadaa] as $kindergarten) {
            foreach ($categoryNames as $index => $catName) {
                $category = Category::create([
                    'kindergarten_id' => $kindergarten->id,
                    'name' => $catName,
                    'order' => $index + 1,
                    'is_active' => true,
                ]);

                foreach ($sectionNames as $secName) {
                    Section::create([
                        'category_id' => $category->id,
                        'kindergarten_id' => $kindergarten->id,
                        'name' => $secName,
                        'max_students' => 30,
                        'is_active' => true,
                    ]);
                }
            }
        }

        echo "✅ تم إنشاء 3 فئات × 4 شعب لكل روضة = 24 شعبة\n\n";

        // ===== 4. إنشاء المواد الدراسية =====
        echo "📖 إنشاء المواد الدراسية...\n";

        $subjects = [
            ['name' => 'اللغة العربية', 'color' => '#EF4444', 'icon' => 'fas fa-book', 'order' => 1],
            ['name' => 'اللغة الإنجليزية', 'color' => '#3B82F6', 'icon' => 'fas fa-language', 'order' => 2],
            ['name' => 'الرياضيات', 'color' => '#10B981', 'icon' => 'fas fa-calculator', 'order' => 3],
            ['name' => 'الحساب الذهني', 'color' => '#F59E0B', 'icon' => 'fas fa-brain', 'order' => 4],
            ['name' => 'القرآن الكريم', 'color' => '#8B5CF6', 'icon' => 'fas fa-quran', 'order' => 5],
            ['name' => 'السلوك', 'color' => '#EC4899', 'icon' => 'fas fa-heart', 'order' => 6],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }

        echo "✅ تم إنشاء 6 مواد دراسية\n\n";

        // ===== 5. أوقات الاستراحات =====
        echo "⏰ إنشاء أوقات الاستراحات...\n";

        BreakTime::create([
            'name' => 'الاستراحة الأولى',
            'start_time' => '09:45',
            'end_time' => '10:05',
            'duration_minutes' => 20,
            'after_period' => 2,
        ]);

        BreakTime::create([
            'name' => 'الاستراحة الثانية',
            'start_time' => '11:35',
            'end_time' => '11:55',
            'duration_minutes' => 20,
            'after_period' => 4,
        ]);

        echo "✅ تم إنشاء استراحتين\n\n";

        // ===== 6. إعدادات النظام =====
        echo "⚙️ إنشاء الإعدادات الأساسية...\n";

        \App\Models\Setting::insert([
            ['key' => 'app_name', 'value' => 'نظام إدارة رياض الأطفال', 'group' => 'general', 'type' => 'text'],
            ['key' => 'current_academic_year', 'value' => '2024-2025', 'group' => 'general', 'type' => 'text'],
            ['key' => 'default_installments', 'value' => '5', 'group' => 'financial', 'type' => 'number'],
            ['key' => 'daily_notification_time', 'value' => '14:00', 'group' => 'notifications', 'type' => 'time'],
            ['key' => 'enable_absence_notifications', 'value' => '1', 'group' => 'notifications', 'type' => 'boolean'],
            ['key' => 'enable_evaluation_notifications', 'value' => '1', 'group' => 'notifications', 'type' => 'boolean'],
            ['key' => 'enable_payment_reminders', 'value' => '1', 'group' => 'notifications', 'type' => 'boolean'],
        ]);

        echo "✅ تم إنشاء الإعدادات الأساسية\n\n";

        // ===== ملخص =====
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "🎉 تم إدخال البيانات الأولية بنجاح!\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

        echo "📋 معلومات تسجيل الدخول:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "👑 المدير العام:\n";
        echo "   البريد: admin@kindergarten.ly\n";
        echo "   كلمة المرور: password123\n\n";

        echo "🏫 مديرة روضة الزهراء:\n";
        echo "   البريد: zahra.admin@kindergarten.ly\n";
        echo "   كلمة المرور: password123\n\n";

        echo "👩‍🏫 معلمة (فاطمة):\n";
        echo "   البريد: fatima@kindergarten.ly\n";
        echo "   كلمة المرور: password123\n\n";

        echo "💰 المحاسبة:\n";
        echo "   البريد: accountant@kindergarten.ly\n";
        echo "   كلمة المرور: password123\n\n";

        echo "👨‍👩‍👧 ولي أمر:\n";
        echo "   البريد: parent@kindergarten.ly\n";
        echo "   كلمة المرور: password123\n\n";

        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "📊 الإحصائيات:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "   🏫 الروضات: 2\n";
        echo "   👥 المستخدمون: 7\n";
        echo "   📚 الفئات: 6\n";
        echo "   🏛️ الشعب: 24\n";
        echo "   📖 المواد: 6\n";
        echo "   ⏰ الاستراحات: 2\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

        echo "🌐 افتح المتصفح على: http://localhost:8000\n";
        echo "✅ جاهز للاستخدام!\n\n";
    }
}