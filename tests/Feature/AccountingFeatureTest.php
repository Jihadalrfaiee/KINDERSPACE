<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Kindergarten;
use App\Models\Student;
use App\Models\FeeStructure;
use App\Models\StudentFee;
use App\Models\Installment;
use App\Models\Expense;
use App\Models\Salary;

class AccountingFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_student_fee_and_auto_generates_installments()
    {
        $kindergarten = Kindergarten::create(['name' => 'K1']);
        $category = \App\Models\Category::create(['kindergarten_id' => $kindergarten->id, 'name' => 'CatA', 'order' => 1, 'is_active' => true]);
        $section = \App\Models\Section::create(['category_id' => $category->id, 'kindergarten_id' => $kindergarten->id, 'name' => 'شعبة أ', 'max_students' => 30, 'is_active' => true]);

        $student = Student::create([
            'kindergarten_id' => $kindergarten->id,
            'section_id' => $section->id,
            'first_name' => 'Test',
            'father_name' => 'Fn',
            'grandfather_name' => 'Gn',
            'family_name' => 'Ln',
            'mother_name' => 'Mother',
            'birth_date' => now()->subYears(5)->toDateString(),
            'father_phone' => '0900000000',
            'registration_number' => '20260001',
            'academic_year' => '2024-2025',
        ]);

        // Create a fee structure with 3 installments
        FeeStructure::create([
            'kindergarten_id' => $kindergarten->id,
            'academic_year' => '2024-2025',
            'tuition_fee' => 3000,
            'transportation_fee' => 0,
            'installments_count' => 3,
            'is_active' => true,
        ]);

        $accountant = User::factory()->create(['role' => 'accountant']);

        $payload = [
            'student_id' => $student->id,
            'kindergarten_id' => $kindergarten->id,
            'academic_year' => '2024-2025',
            'total_tuition' => 3000,
            'total_transportation' => 0,
            'discount_amount' => 0,
            'net_amount' => 3000,
            'paid_amount' => 0,
            'remaining_amount' => 3000,
            'status' => 'pending',
        ];

        $this->actingAs($accountant)->post(route('student-fees.store'), $payload)->assertRedirect(route('student-fees.index'));

        $this->assertDatabaseHas('student_fees', ['student_id' => $student->id, 'net_amount' => 3000]);

        $sf = StudentFee::first();
        $this->assertEquals(3, $sf->installments()->count());
    }

    public function test_create_installment()
    {
        $kindergarten = Kindergarten::create(['name' => 'K2']);
        $category = \App\Models\Category::create(['kindergarten_id' => $kindergarten->id, 'name' => 'CatB', 'order' => 1, 'is_active' => true]);
        $section = \App\Models\Section::create(['category_id' => $category->id, 'kindergarten_id' => $kindergarten->id, 'name' => 'شعبة ب', 'max_students' => 30, 'is_active' => true]);

        $student = Student::create([
            'kindergarten_id' => $kindergarten->id,
            'section_id' => $section->id,
            'first_name' => 'I1',
            'father_name' => 'F',
            'grandfather_name' => 'G',
            'family_name' => 'L',
            'mother_name' => 'M',
            'birth_date' => now()->subYears(4)->toDateString(),
            'father_phone' => '0911111111',
            'registration_number' => '20260002',
            'academic_year' => '2024-2025',
        ]);

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

        $accountant = User::factory()->create(['role' => 'accountant']);

        $payload = [
            'student_fee_id' => $sf->id,
            'student_id' => $student->id,
            'installment_number' => 1,
            'amount' => 1000,
            'due_date' => now()->addMonth()->toDateString(),
            'status' => 'pending',
        ];

        $this->actingAs($accountant)->post(route('installments.store'), $payload)->assertRedirect(route('installments.index'));

        $this->assertDatabaseHas('installments', ['student_fee_id' => $sf->id, 'amount' => 1000]);
    }

    public function test_create_expense()
    {
        $kindergarten = Kindergarten::create(['name' => 'K3']);
        $accountant = User::factory()->create(['role' => 'accountant']);

        $payload = [
            'kindergarten_id' => $kindergarten->id,
            'category' => 'مستلزمات',
            'description' => 'أوراق طباعة',
            'amount' => 150,
            'date' => now()->toDateString(),
        ];

        $this->actingAs($accountant)->post(route('expenses.store'), $payload)->assertRedirect(route('expenses.index'));

        $this->assertDatabaseHas('expenses', ['category' => 'مستلزمات', 'amount' => 150]);
    }

    public function test_create_salary()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'teacher']);

        $payload = [
            'user_id' => $user->id,
            'base_salary' => 1000,
            'bonus' => 100,
            'deductions' => 0,
            'net_salary' => 1100,
            'month' => now()->month,
            'year' => now()->year,
            'status' => 'pending',
        ];

        $this->actingAs($admin)->post(route('salaries.store'), $payload)->assertRedirect(route('salaries.index'));

        $this->assertDatabaseHas('salaries', ['user_id' => $user->id, 'net_salary' => 1100]);
    }
}
