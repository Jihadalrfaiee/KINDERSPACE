<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ===== 1. الروضات =====
        Schema::create('kindergartens', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ===== 2. المستخدمون (تعديل جدول Laravel الافتراضي) =====
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kindergarten_id')->nullable()->constrained('kindergartens')->cascadeOnDelete();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('national_id')->nullable();
            $table->enum('role', ['super_admin', 'admin', 'teacher', 'accountant', 'parent']);
            $table->string('telegram_chat_id')->nullable();
            $table->string('profile_photo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->string('academic_qualification')->nullable();
            $table->text('previous_experience')->nullable();
            $table->integer('years_of_experience')->default(0);
            $table->string('job_title')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // ===== 3. الفئات (المستويات) =====
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kindergarten_id')->constrained('kindergartens')->cascadeOnDelete();
            $table->string('name');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ===== 4. الشعب =====
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('kindergarten_id')->constrained('kindergartens')->cascadeOnDelete();
            $table->string('name');
            $table->integer('max_students')->default(30);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ===== 5. المواد الدراسية =====
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color')->default('#3B82F6');
            $table->string('icon')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ===== 6. تعيين المعلمات =====
        Schema::create('teacher_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->string('academic_year');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ===== 7. الطلاب =====
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kindergarten_id')->constrained('kindergartens')->cascadeOnDelete();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('first_name');
            $table->string('father_name');
            $table->string('grandfather_name');
            $table->string('family_name');
            $table->string('mother_name');
            $table->date('birth_date');
            $table->integer('siblings_count')->default(0);
            $table->integer('birth_order')->default(1);
            $table->string('registration_number')->unique();
            $table->string('nationality')->default('ليبي');
            $table->string('address')->nullable();
            $table->enum('father_marital_status', ['متزوج', 'مطلق', 'أرمل', 'منفصل'])->default('متزوج');
            $table->enum('mother_marital_status', ['متزوجة', 'مطلقة', 'أرملة', 'منفصلة'])->default('متزوجة');
            $table->string('student_national_id')->nullable();
            $table->string('father_national_id')->nullable();
            $table->boolean('needs_transportation')->default(false);
            $table->boolean('needs_bathroom_care')->default(false);
            $table->text('previous_diseases')->nullable();
            $table->string('father_phone');
            $table->string('mother_phone')->nullable();
            $table->string('home_phone')->nullable();
            $table->enum('status', ['active', 'inactive', 'graduated', 'withdrawn'])->default('active');
            $table->string('academic_year');
            $table->timestamps();
            $table->softDeletes();
        });

        // ===== 8. مرفقات الطلاب =====
        Schema::create('student_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->enum('type', ['personal_photo', 'birth_certificate', 'family_book', 'father_id', 'vaccination_book', 'other']);
            $table->string('file_path');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->timestamps();
        });

        // ===== 9. حضور الطلاب =====
        Schema::create('student_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->foreignId('recorded_by')->constrained('users')->cascadeOnDelete();
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'late', 'excused'])->default('present');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['student_id', 'date']);
        });

        // ===== 10. حضور الموظفين =====
        Schema::create('employee_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->enum('status', ['present', 'absent', 'late', 'excused', 'vacation'])->default('present');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'date']);
        });

        // ===== 11. تقييمات الطلاب =====
        Schema::create('student_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->enum('grade', ['ممتاز', 'جيد جداً', 'متوسط', 'ضعيف']);
            $table->text('notes')->nullable();
            $table->date('evaluation_date');
            $table->integer('week_number');
            $table->string('academic_year');
            $table->timestamps();
        });

        // ===== 12. تقييم السلوك =====
        Schema::create('behavior_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->enum('grade', ['ممتاز', 'جيد جداً', 'متوسط', 'ضعيف']);
            $table->text('notes')->nullable();
            $table->date('evaluation_date');
            $table->integer('week_number');
            $table->string('academic_year');
            $table->timestamps();
        });

        // ===== 13. تقييم الموظفين =====
        Schema::create('employee_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('evaluated_by')->constrained('users')->cascadeOnDelete();
            $table->decimal('score', 5, 2);
            $table->integer('month');
            $table->integer('year');
            $table->text('strengths')->nullable();
            $table->text('weaknesses')->nullable();
            $table->text('recommendations')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'month', 'year']);
        });

        // ===== 14. البرنامج الأسبوعي =====
        Schema::create('weekly_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->enum('day', ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday']);
            $table->integer('period_number');
            $table->time('start_time');
            $table->time('end_time');
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->string('academic_year');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ===== 15. الاستراحات =====
        Schema::create('break_times', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('duration_minutes')->default(20);
            $table->integer('after_period');
            $table->timestamps();
        });

        // ===== 16. الدروس اليومية =====
        Schema::create('daily_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->date('date');
            $table->text('lesson_content');
            $table->text('homework')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // ===== 17. تحضير المعلمات =====
        Schema::create('teacher_preparations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->date('date');
            $table->boolean('is_prepared')->default(false);
            $table->text('preparation_notes')->nullable();
            $table->foreignId('checked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // ===== 18. الوسائل التعليمية =====
        Schema::create('teaching_aids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->date('date');
            $table->timestamps();
        });

        // ===== 19. هيكل الرسوم =====
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kindergarten_id')->constrained('kindergartens')->cascadeOnDelete();
            $table->string('academic_year');
            $table->decimal('tuition_fee', 10, 2);
            $table->decimal('transportation_fee', 10, 2)->default(0);
            $table->integer('installments_count')->default(5);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ===== 20. رسوم الطلاب =====
        Schema::create('student_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('kindergarten_id')->constrained('kindergartens')->cascadeOnDelete();
            $table->string('academic_year');
            $table->decimal('total_tuition', 10, 2);
            $table->decimal('total_transportation', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->string('discount_reason')->nullable();
            $table->decimal('net_amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('remaining_amount', 10, 2);
            $table->enum('status', ['pending', 'partial', 'paid'])->default('pending');
            $table->timestamps();
        });

        // ===== 21. الأقساط =====
        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_fee_id')->constrained('student_fees')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->integer('installment_number');
            $table->decimal('amount', 10, 2);
            $table->date('due_date');
            $table->date('paid_date')->nullable();
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->enum('status', ['pending', 'paid', 'partial'])->default('pending');
            $table->string('receipt_number')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // ===== 22. المصاريف =====
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kindergarten_id')->constrained('kindergartens')->cascadeOnDelete();
            $table->string('category');
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->date('date');
            $table->string('receipt_image')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        // ===== 23. الرواتب =====
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('base_salary', 10, 2);
            $table->decimal('bonus', 10, 2)->default(0);
            $table->decimal('deductions', 10, 2)->default(0);
            $table->decimal('net_salary', 10, 2);
            $table->integer('month');
            $table->integer('year');
            $table->date('paid_date')->nullable();
            $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'month', 'year']);
        });

        // ===== 24. الإشعارات =====
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $table->enum('type', ['absence', 'evaluation', 'homework', 'daily_content', 'forgotten_item', 'payment_reminder', 'general', 'holiday', 'appointment', 'teacher_alert', 'behavior']);
            $table->string('title');
            $table->text('message');
            $table->enum('channel', ['telegram', 'system', 'both'])->default('both');
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamps();
        });

        // ===== 25. المواعيد =====
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->text('reason')->nullable();
            $table->text('admin_notes')->nullable();
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // ===== 26. الأنشطة =====
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kindergarten_id')->constrained('kindergartens')->cascadeOnDelete();
            $table->enum('type', ['trip', 'celebration', 'competition', 'activity', 'other']);
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('location')->nullable();
            $table->decimal('cost', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        // ===== 27. العطل =====
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('type', ['national', 'religious', 'official', 'other']);
            $table->text('description')->nullable();
            $table->boolean('notify_parents')->default(true);
            $table->boolean('notify_employees')->default(true);
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        // ===== 28. المواصلات =====
        Schema::create('transportation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kindergarten_id')->constrained('kindergartens')->cascadeOnDelete();
            $table->string('driver_name');
            $table->string('driver_phone');
            $table->string('driver_national_id')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->string('vehicle_plate')->nullable();
            $table->integer('capacity')->default(20);
            $table->string('route_name')->nullable();
            $table->text('route_description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ===== 29. تعيين الطلاب للمواصلات =====
        Schema::create('student_transportation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('transportation_id')->constrained('transportation')->cascadeOnDelete();
            $table->string('pickup_location')->nullable();
            $table->time('pickup_time')->nullable();
            $table->string('dropoff_location')->nullable();
            $table->time('dropoff_time')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ===== 30. سجل العمليات =====
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });

        // ===== 31. الإعدادات =====
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->string('type')->default('text');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // ===== 32. Password Reset (Laravel الافتراضي) =====
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // ===== 33. Sessions =====
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('student_transportation');
        Schema::dropIfExists('transportation');
        Schema::dropIfExists('holidays');
        Schema::dropIfExists('activities');
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('notification_logs');
        Schema::dropIfExists('salaries');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('installments');
        Schema::dropIfExists('student_fees');
        Schema::dropIfExists('fee_structures');
        Schema::dropIfExists('teaching_aids');
        Schema::dropIfExists('teacher_preparations');
        Schema::dropIfExists('daily_lessons');
        Schema::dropIfExists('break_times');
        Schema::dropIfExists('weekly_schedules');
        Schema::dropIfExists('employee_evaluations');
        Schema::dropIfExists('behavior_evaluations');
        Schema::dropIfExists('student_evaluations');
        Schema::dropIfExists('employee_attendances');
        Schema::dropIfExists('student_attendances');
        Schema::dropIfExists('student_documents');
        Schema::dropIfExists('students');
        Schema::dropIfExists('teacher_assignments');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('sections');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('users');
        Schema::dropIfExists('kindergartens');
    }
};