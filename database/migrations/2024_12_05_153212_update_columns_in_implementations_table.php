<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('implementations', function (Blueprint $table) {
            $table->dropColumn('is_sectoral');
            $table->string('payroll_status')->after('days_of_work');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('implementations', function (Blueprint $table) {
            $table->tinyInteger('is_sectoral');
            $table->dropColumn('payroll_status');
        });
    }
};
