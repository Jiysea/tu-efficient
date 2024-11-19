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
        Schema::table('batches', function (Blueprint $table) {
            $table->string('district')->after('batch_num')->nullable();
            $table->string('sector_title')->after('batch_num')->nullable();
            $table->string('barangay_name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            $table->dropColumn('district');
            $table->dropColumn('sector_title');
            $table->string('barangay_name')->nullable(false)->change();
        });
    }
};
