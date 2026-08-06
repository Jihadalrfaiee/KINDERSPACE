<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('fee_structures', function (Blueprint $table) {
            $table->string('schedule_type')->default('monthly')->after('installments_count');
            $table->integer('first_due_offset_days')->default(7)->after('schedule_type');
            $table->integer('interval_months')->default(1)->after('first_due_offset_days');
            $table->text('custom_schedule')->nullable()->after('interval_months');
        });
    }

    public function down()
    {
        Schema::table('fee_structures', function (Blueprint $table) {
            $table->dropColumn(['schedule_type', 'first_due_offset_days', 'interval_months', 'custom_schedule']);
        });
    }
};