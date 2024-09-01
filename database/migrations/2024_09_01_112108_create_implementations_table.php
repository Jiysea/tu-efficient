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
        Schema::create('implementations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_id')->constrained();
            $table->string('project_num')->unique();
            $table->string('project_title', 500)->nullable();
            $table->string('purpose');
            $table->string('province');
            $table->string('city_municipality');
            $table->string('district');
            $table->bigInteger('budget_amount');
            $table->integer('total_slots');
            $table->integer('days_of_work');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('implementations');
    }
};
