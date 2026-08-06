<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Kindergarten;
use App\Models\Category;
use App\Models\Section;
use App\Models\Student;
use App\Models\FeeStructure;
use App\Models\StudentFee;
use App\Models\Installment;

class AccountingReportsTest extends TestCase
{
    use RefreshDatabase;

    public function test_monthly_report_returns_expected_values()
    {
        $kindergarten = Kindergarten::create(['name' => 'KR']);
        $category = Category::create(['kindergarten_id' => $kindergarten->id, 'name' => 'Cat', 'order' => 1, 'is_active' => true]);
        $section = Section::create(['category_id' => $category->id, 'kindergarten_id' => $kindergarten->id, 'name' => 'S', 'max_students' => 10, 'is_active' => true]);

        $student = Student::create([
            'kindergarten_id' => $kindergarten->id,
            'section_id' => $section->id,
            'first_name' => 'R',
            'father_name' => 'F',
            'grandfather_name' => 'G',
            'family_name' => 'L',
            'mother_name' => 'M',
            'birth_date' => now()->subYears(5)->toDateString(),
            'father_phone' => '0999999999',
            'registration_number' => '20260010',
            'academic_year' => '2024-2025',
        ]);

        // Fee structure with 2 installments
        FeeStructure::create([
            'kindergarten_id' => $kindergarten->id,
            'academic_year' => '2024-2025',
            'tuition_fee' => 2000,
            'transportation_fee' => 0,
            'installments_count' => 2,
            'is_active' => true,
            'first_due_offset_days' => 0,
            'interval_months' => 1,
        ]);

        // Create student fee and generate installments via controller method
        $sf = StudentFee::create([
            'student_id' => $student->id,
            'kindergarten_id' => $kindergarten->id,
            'academic_year' => '2024-2025',
            'total_tuition' => 2000,
            'total_transportation' => 0,
            'discount_amount' => 0,
            'net_amount' => 2000,
            'paid_amount' => 0,
            'remaining_amount' => 2000,
            'status' => 'pending',
        ]);

        // call the generation (controller private method called indirectly via storing flow in prior tests was used there)
        // Here simulate generating installments similar to StudentFeeController behavior
        $controller = new \App\Http\Controllers\StudentFeeController();
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('generateInstallmentsForStudentFee');
        $method->setAccessible(true);
        $method->invoke($controller, $sf);

        $this->assertDatabaseHas('installments', ['student_fee_id' => $sf->id]);

        // Request report for current month
        $accountant = User::factory()->create(['role' => 'accountant']);
        $this->actingAs($accountant)->get(route('reports.monthly'))->assertStatus(200)->assertSee('إجمالي الإيرادات المتوقعة');
    }

    public function test_student_balance_report()
    {
        $kindergarten = Kindergarten::create(['name' => 'KB']);
        $category = Category::create(['kindergarten_id' => $kindergarten->id, 'name' => 'Cat', 'order' => 1, 'is_active' => true]);
        $section = Section::create(['category_id' => $category->id, 'kindergarten_id' => $kindergarten->id, 'name' => 'S', 'max_students' => 10, 'is_active' => true]);

        $student = Student::create([
            'kindergarten_id' => $kindergarten->id,
            'section_id' => $section->id,
            'first_name' => 'SB',
            'father_name' => 'F',
            'grandfather_name' => 'G',
            'family_name' => 'L',
            'mother_name' => 'M',
            'birth_date' => now()->subYears(6)->toDateString(),
            'father_phone' => '0988888888',
            'registration_number' => '20260011',
            'academic_year' => '2024-2025',
        ]);

        $sf = StudentFee::create([
            'student_id' => $student->id,
            'kindergarten_id' => $kindergarten->id,
            'academic_year' => '2024-2025',
            'total_tuition' => 1500,
            'total_transportation' => 0,
            'discount_amount' => 0,
            'net_amount' => 1500,
            'paid_amount' => 500,
            'remaining_amount' => 1000,
            'status' => 'partial',
        ]);

        $accountant = User::factory()->create(['role' => 'accountant']);
        $this->actingAs($accountant)->get(route('reports.student', ['student' => $student->id]))->assertStatus(200)->assertSee('رصيد الطالب');
    }
}
