KINDERSPACE — دليل المشروع (ملف README)
=====================================

ملخص المشروع
------------
KINDERSPACE هو نظام إدارة حضانة/روضة مكتوب بـ Laravel (نسخة متوافقة مع ^12) مخصّص لإدارة بيانات الروضات، الطلاب، الحسابات، الجداول، والواجهات الإدارية.

هذه الوثيقة تهدف إلى إعطاء أي مبرمج جديد خريطة طريق كاملة للمشروع: بنية الملفات، أماكن المشكلات الشائعة، تعليمات التشغيل المحلية والإنتاجية، ونصائح تطويرية/أمنية.

معلومات عامة
-------------
- لغة/إطار العمل: PHP + Laravel
- متطلبات تطوير: PHP 8.2+، Composer 2.x, SQLite/MySQL/Postgres (محليًا استخدمنا sqlite للسرعة)، Redis (مستحسن للجلسات/الطابور)
- مدير الحزم: Composer
- نظام القوالب: Blade
- اختبارات: PHPUnit
- CI: GitHub Actions (.github/workflows/ci.yml)

شجرة المشروع (مبسطة ومهمة للمطوّر)
----------------------------------
- app/
  - Models/           -> نماذج Eloquent (User, Student, FeeStructure, StudentFee, Installment, Expense, Salary, BreakTime,...)
  - Http/
    - Controllers/    -> متحكمات MVC (Auth, Dashboard, Students, Finance controllers...)
    - Middleware/     -> Middlewares (مثال: EnsureUserHasRole.php, throttle applied to /login)
    - Requests/       -> (إن وجدت) طلبات التحقق المخصّصة
- bootstrap/          -> تسجيل الـ middleware وبعض إعدادات التطبيق
- config/             -> إعدادات التطبيق (cache, database, queue, mail...)
- routes/
  - web.php           -> تعريف المسارات العامة (login, dashboard, students, finance resources...)
- resources/
  - views/            -> Blade views
    - finance/        -> واجهات المحاسبة (fee_structures, student_fees, installments, expenses, salaries)
    - layouts/        -> القالب العام
    - auth/           -> صفحات تسجيل الدخول
- database/
  - migrations/       -> ملفات الهجرة للمخطط (schema)
  - seeders/          -> seeders لإدخال بيانات افتراضية (DatabaseSeeder, StudentLoadSeeder,...)
  - factories/        -> factories لتوليد بيانات اختبارية
- tests/              -> اختبارات Unit و Feature (مثال: AuthThrottleRoleTest.php)
- .github/workflows/  -> إعداد CI (تشغيل composer, migrate, phpunit على PR/Push)
- composer.json
- .env.example        -> مثال لملف البيئة
- .env.production     -> قالب إعداد الإنتاج (موصى بإنشائه وملؤه قبل النشر)

ملفات ومواقع مهمة ووصف وظيفتها
--------------------------------
- routes/web.php
  - بداية الرجوع للمسارات الأساسية: صفحة تسجيل الدخول، لوحة التحكم، ومسارات الموارد (students, finance resources).
  - ملاحظة: تم تطبيق ميدل وير throttle على مسارات تسجيل الدخول وقيود role على مسارات المحاسبة.

- app/Http/Middleware/EnsureUserHasRole.php
  - ميدل وير بسيط لفحص امتلاك المستخدم للدور المطلوب. يُستخدم في حماية مسارات حساسة.

- database/migrations/2026_06_04_085033_create_all_kindergarten_tables.php
  - يحتوي على الجداول الأساسية للاستخدام (students, fee_structures, student_fees, installments, expenses, salaries...) — راجع قبل إجراء أي حذف/تعديل على مهاجرات أخرى.

- database/migrations/
  - ملاحظة مهمة: تم العثور على ملف(ملفات) مهاجرات مكررة تتعلق بالـ roles/permissions. أي حذف/تغيير في هذه المهاجرات قد يؤثر على بيئات موجودة — راجع الفريق قبل دمج تغييرات مهاجرات.

- database/seeders/DatabaseSeeder.php
  - يقوم بإنشاء بيانات أولية: kindergartens, users (بما في ذلك accountant)، break times، settings...
  - ملاحظة: Seeder كان يعتمد على نموذج BreakTime الذي كان مفقودًا محليًا فتمت إضافته (app/Models/BreakTime.php) لتشغيل seeder.

- app/Models/*
  - FeeStructure.php, StudentFee.php, Installment.php, Expense.php, Salary.php
  - هذه ملفات النموذج للمحاسبة (تمت إضافتها كـ Phase 1 scaffold). تحتوي على $fillable و $casts وعلاقات أساسية.

- app/Http/Controllers/*
  - FeeStructureController, StudentFeeController, InstallmentController, ExpenseController, SalaryController
  - متحكمات موارد CRUD أساسية للمحاسبة مع validation بسيط.

- resources/views/finance/
  - تحتوي على صفحات CRUD أساسية لقوائم Fee Structures و Student Fees، وقوائم للأقساط/المصروفات/الرواتب — تحتاج تحسينات واجهة وميزات كاملة.

- tests/Feature/
  - AuthThrottleRoleTest.php — اختبار لقيود throttle والـ role middleware.

قضايا معروفة وحلول مختصرة
-------------------------
1) Duplicate migrations (مهاجرات مكررة)
   - المشكلة: وجود ملفات هجرة مكررة تسبب فشل الهجرة.
   - الحل الممكن محلياً: حذف النسخ المكررة أو توحيدها، لكن لا تدمج تغييرات حذف المهاجرات بدون موافقة الفريق/المدراء لأن ذلك قد يكسر بيئات أخرى.
   - التحقق: راجع تاريخ git لمعرفة لماذا وُجدت النسخ المكررة؛ استشر المبرمجين الآخرين.

2) Seeder يفشل بسبب نموذج مفقود (BreakTime)
   - الحل: إضافة app/Models/BreakTime.php أو تعديل DatabaseSeeder لإعفاء الاعتماد.

3) CI يفشل بسبب actions غير موجودة (مثال: shivammathur/setup-php@v4)
   - الحل: استبدال الإجراء بإصدار مدعوم (مثال @v2) أو استخدام actions/checkout@v4 وshivammathur/setup-php@v2.

4) Factory/Tests mismatch
   - المشكلة: UserFactory كان ينتج عمودًا غير موجود (email_verified_at)
   - الحل: تعديل factories لتتوافق مع schema الفعلية أو تعديل الهجرة لإضافة العمود.

تشغيل المشروع محلياً (خطوات سريعة)
----------------------------------
1) استنساخ المستودع وفتح المجلد:
   git clone <repo_url>
   cd KINDERSPACE

2) تثبيت الاعتماديات:
   composer install --no-interaction --prefer-dist

3) إعداد ملف البيئة (محلي - sqlite مثال):
   cp .env.example .env
   - أو أنشئ .env.production كمثال للإنتاج
   php artisan key:generate

4) إعداد قاعدة بيانات SQLite اختبارية:
   touch database/database.sqlite
   تحديث .env: DB_CONNECTION=sqlite

5) تشغيل الهجرات والـ seeders (محلي):
   php artisan migrate --seed
   - إذا واجهت مشاكل بسبب مهاجرات مكررة: راجع database/migrations/ وحل التعارضات قبل الدمج.

6) تشغيل السيرفر المحلي:
   php artisan serve --host=127.0.0.1 --port=8000

7) تشغيل الاختبارات:
   ./vendor/bin/phpunit --teamcity (أو php vendor/bin/phpunit)

تجهيز للإنتاج (نصائح أساسية)
---------------------------
- إعداد .env.production كامل بقيم MySQL/Redis/Queue/Mail
- DB: استخدم MySQL أو PostgreSQL بدلاً من sqlite (better concurrency)
- Cache & Sessions: استخدم Redis
- Queue: ربط queue driver (redis) وتشغيل queue worker (supervisor/systemd)
- Storage: php artisan storage:link
- APP_DEBUG=false, APP_ENV=production
- HTTPS/Certificates (Nginx/Apache behind proxy)
- إعداد نسخ احتياطي للـ DB وجدول مهام cron لتفريغ النسخ
- مراقبة (Logging/Monitoring): Sentry أو Logtail، و Instrumentation لمقاييس الأداء

الأمان (قائمة مراجعة سريعة)
-------------------------
- APP_DEBUG=false
- استخدام HTTPS فقط
- تأمين متغيرات البيئة وعدم رفعها للمستودع
- تحديث الحزم بانتظام (Dependabot/Snyk)
- تفعيل throttle على نقاط الدخول (login) — مُطبَّق
- تأمين المسارات الحساسة بواسطة role middleware و/أو Gates/Policies
- تحقق من صحة المدخلات في Controllers (validation) — موجود في الأساسيات
- تخزين كلمات المرور باستخدام bcrypt (Laravel handles)
- سجلات التدقيق (audit_logs) لتتبع العمليات المالية (توصية بالتمكين)

أداء وتحمل (توصيات)
------------------
- اختبار التحميل: استخدم seeder (StudentLoadSeeder) لملء بيانات ثم إجراء اختبارات كتابة/قراءة متزامنة في بيئة staging
- قاعدة بيانات إنتاج: MySQL/Postgres مع نسخ احتياطي/استرجاع
- استخدم Redis للـ cache/session/queue
- استضافة: PHP-FPM + Nginx، وضع عدّة نسخ خلف load balancer لزيادة السعة

كم مستخدم متزامن؟
-----------------
لا يمكن تحديد رقم ثابت هنا بدون معرفة موارد الخادم (CPU, RAM، قواعد DB)، أما التقدير العام:
- بيئة بسيطة (1 vCPU, 1GB RAM, MySQL single instance): ~50-200 مستخدم متزامن حسب الاستخدام (قراءات/كتابات)
- للتعامل مع مئات/آلاف المستخدمين: استخدم عدة خوادم، قواعد بيانات مُدارة وتحسين استعلامات/indexes، وRedis.

كيفية التعامل مع مشكلة (مثال: "صفحة إضافة طالب لا تعمل")
---------------------------------------------------------
1. راجع سجل الأخطاء (storage/logs/laravel.log) لتحديد الاستثناء/الاستدعاء.
2. تأكد من تطابق الحقول في الهجرة مع حقول الـ form (name, required fields).
3. تحقق من الController المتعلق (app/Http/Controllers/StudentController.php) للاعتراضات والvalidation
4. شغّل php artisan tinker أو اختبارات الوحدة للوصول إلى المشكلة محليًا
5. إن كانت المشكلة مرتبطة بالقاعدة: تأكد من تفعيل المهاجرات ووجود الأعمدة، أو أعد تشغيل seeder إذا كانت بيانات مفقودة

ملاحظات عن التطوير الجاري (حالة الوقت الحالي)
---------------------------------------------
- تم تنفيذ حماية throttle للدخول (login throttling).
- تم إضافة middleware بسيط للدور role-based access control (EnsureUserHasRole).
- تم إصلاح مشكلة missing BreakTime model وأضيفت أوتوماتيكياً محليًا لتشغيل seeders.
- تم اكتشاف وإصلاح مشاكل CI المتعلقة بإصدار action المستخدم في workflow.
- تم إضافة scaffolding للمرحلة الأولى من نظام المحاسبة (Models, Controllers, Views الأساسية)

PRs وCI
-------
- راجع PRs الحالية: PR#1 (security + throttle + role) و PR#2 (feature/accounting-crud)
- CI: .github/workflows/ci.yml يشغّل composer install, migrate, phpunit. إذا فشل الـ CI، راجع سجلات الـ Actions لتحديد الخطأ (غالبًا إصدار action أو mismatch في schema/tests).

قائمة تحقق قبل الدمج لخصوص التغييرات الحساسة (مهاجرات/بيانات)
-------------------------------------------------------------
1. التحقق مع الفريق: هل أي بيئة تعتمد على مهاجرات مكررة؟
2. تشغيل الهجرات والـ seeders على بيئة staging بعد الدمج المؤقت
3. إضافة اختبارات feature تغطي التغييرات (لا سيما للعمليات المالية)
4. تفعيل CI ليمنع الدمج إن فشل أي اختبار

اقتراحات تطويرية للمراحل التالية (ملخص سريع)
---------------------------------------------
- المرحلة 2: وظائف محاسبية متقدمة (توليد أقساط أوتوماتيكي، تقارير، إشعارات، توليد إيصالات PDF، ربط بوابة دفع)
- المرحلة 3: تحسينات تشغيلية (اختبارات شاملة، queue للمهام الثقيلة، مراقبة/نسخ احتياطي)
- تمكين audit_logs لكل التغييرات المالية

هل تريد ملف README بالإنجليزية أيضًا؟
-----------------------------------
يمكن إنشاء نسخة إنجليزية مفصّلة بنفس المحتوى إن رغبت.

خاتمة
-----
تم إعداد هذا الملف ليكون نقطة انطلاق لأي مطور جديد للعمل على المشروع. إن رغبت، أستطيع:
- تحديث README ليشمل خريطة كاملة للـ routes (قائمة مسارات مع وصف)
- إضافة مخططات ER لهيكل القاعدة
- توليد docs أوتوماتيكيًا (Swagger/OpenAPI) لواجهات الـ API إذا وُجدت


قائمة المسارات التفصيلية (Route => Controller/Closure => View)
---------------------------------------------------------------

فيما يلي قائمة المسارات الأساسية الموجودة في routes/web.php مع الإشارة إلى ما يتولّى كل مسار (Controller أو Closure) والـ Blade view المستخدمة إن وجدت.

1) عام / اختبارات
- GET /test => Closure — يعيد نص "Laravel يعمل بنجاح ✅" (اختبار سريع)

2) الدخول / المصادقة
- GET / => Closure — يعيد إعادة توجيه للمستخدم المسجل إلى dashboard أو إلى login
- GET /login => Closure — view: resources/views/test-login.blade.php (named route: login)
- GET /test-login => Closure — view: resources/views/test-login.blade.php (named route: test-login)
- POST /login => Closure — معالجة بيانات الاعتماد، يستخدم middleware: throttle:6,1 (named route: login.submit)
- POST /test-login => Closure — معالجة بيانات الاعتماد، يستخدم middleware: throttle:6,1
- POST /logout => Closure — يقوم بتسجيل الخروج (named route: logout)

3) مسارات محمية بالمصادقة (middleware: auth)
- GET /dashboard => Closure — view: resources/views/dashboard.blade.php (named: dashboard)

إدارة الطلاب (داخل مجموعة auth)
- GET /students => Closure — view: resources/views/students/index.blade.php (named: students.index)
- GET /students/create => Closure — view: resources/views/students/create.blade.php (named: students.create)
- POST /students => Closure — (students.store) معالجة حفظ طالب جديد
- GET /students/{student}/edit => Closure — view: resources/views/students/edit.blade.php (named: students.edit)
- PUT /students/{student} => Closure — (students.update)
- GET /students/{student} => Closure — view: resources/views/students/show.blade.php (named: students.show)
- DELETE /students/{student} => Closure — (students.destroy)

4) إدارة الصلاحيات والمستخدمين (middleware: auth, role:super_admin,admin)
- Resource routes for roles => RoleController (routes: index/create/store/show/edit/update/destroy)
- Resource routes for users => UserController
  (المسارات هي: /roles/* و /users/* — تحقق من app/Http/Controllers/RoleController و UserController)

5) موارد المحاسبة (middleware: auth, role:super_admin,admin,accountant)
- Resource routes for fee-structures => FeeStructureController (views: resources/views/finance/fee_structures/*.blade.php)
  - index, create, store, show, edit, update, destroy
- Resource routes for student-fees => StudentFeeController (views: resources/views/finance/student_fees/*.blade.php)
- Resource routes for installments => InstallmentController (views: resources/views/finance/installments/*.blade.php)
- Resource routes for expenses => ExpenseController (views: resources/views/finance/expenses/*.blade.php)
- Resource routes for salaries => SalaryController (views: resources/views/finance/salaries/*.blade.php)

ملاحظات عن المسارات
- العديد من مسارات CRUD مُنفَّذة كـ resource routes وتستخدم متحكمات موجودة تحت app/Http/Controllers.
- مسارات الطلاب (students) مُعرّفة مباشرة كـ Closures داخل web.php (وليس عبر StudentController)، لذلك لتعديل سلوكهم راجع web.php مباشرة أو قم بتحويل هذه closures إلى متحكم إذا رغبت.
- يوجد throttle مطبق على POST /login و POST /test-login لتقليل محاولات تخمين كلمات السر.

رابط ومخطط ER لقاعدة البيانات
-----------------------------
تم إنشاء مخطط ER بصيغة SVG يصف الجداول الأساسية والعلاقات بينها (مهم للمطورين الجدد لفهم البنية بسرعة).
- المسار داخل المستودع: docs/er-diagram.svg

يمكن عرض المخطط مباشرة من GitHub بعد رفع الفرع أو معاينته محليًا بفتح الملف docs/er-diagram.svg.

أمثلة لحل المشاكل المتعلقة بالمسارات/الواجهات
----------------------------------------------
- إذا لم تظهر صفحة الطلاب:
  1) تأكد أن المستخدم مسجل دخول (middleware auth). 2) راجع web.php عند تعريف /students. 3) تأكد من وجود view في resources/views/students/index.blade.php. 4) راجع سجلات الأخطاء storage/logs/laravel.log.

- إذا كانت مسارات الموارد للمحاسبة تعطي 404:
  1) تأكد من تفعيل auth + role للمستخدم الحالي (role must be super_admin/admin/accountant). 2) تأكد من وجود Controllers المذكورة في app/Http/Controllers. 3) تحقق من أسماء الملفات والمسارات، وأعد تشغيل السيرفر إن لزم.

انتهى الإضافة — مخطط ER مرفق داخل مجلد docs (er-diagram.svg).

آخر تحديث: 2026-08-05 20:43:00 +03:00
