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
        Schema::table('system_logs', function (Blueprint $table) {
            $table->string('alternative_sender')->nullable()->after('users_id');
            $table->json('data')->after('description')->nullable();
            $table->string('regional_office')->nullable()->after('data');
            $table->string('field_office')->nullable()->after('regional_office');
            $table->string('log_type')->after('field_office');
            $table->timestamp('log_timestamp')->after('log_type')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system_logs', function (Blueprint $table) {
            $table->dropColumn('alternative_sender');
            $table->dropColumn('data');
            $table->dropColumn('regional_office');
            $table->dropColumn('field_office');
            $table->dropColumn('log_type');
            $table->timestamp('log_timestamp')->after('users_id')->change();
        });
    }
};
