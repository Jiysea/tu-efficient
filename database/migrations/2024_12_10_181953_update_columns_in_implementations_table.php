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
            $table->renameColumn('payroll_status', 'status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('implementations', function (Blueprint $table) {
            $table->renameColumn('status', 'payroll_status');
        });
    }
};
