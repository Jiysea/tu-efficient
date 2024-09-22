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
        Schema::table('codes', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable()->change();
            $table->timestamp('updated_at')->nullable();
            $table->renameColumn('accessible', 'is_accessible');

            // $table->unique(['batches_id', 'is_accessible']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('codes', function (Blueprint $table) {
            $table->timestamp('created_at')->change();
            $table->dropColumn('updated_at');
            $table->renameColumn('is_accessible', 'accessible');

            // $table->dropForeign(['batches_id']);
            // $table->dropUnique(['batches_id', 'is_accessible']);
            // $table->foreign('batches_id')->references('id')->on('batches');
        });
    }
};
