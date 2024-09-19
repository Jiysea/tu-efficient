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
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batches_id')->constrained();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('extension_name')->nullable();
            $table->date('birthdate');
            $table->string('barangay_name');
            $table->string('contact_num');
            $table->string('occupation')->nullable();
            $table->string('avg_monthly_income')->nullable();
            $table->string('city_municipality');
            $table->string('province');
            $table->string('district');
            $table->string('type_of_id');
            $table->string('id_number');
            $table->string('e_payment_acc_num')->nullable();
            $table->string('beneficiary_type'); // ['underemployed', 'calamity victim']
            $table->string('sex'); // ['male', 'female']
            $table->string('civil_status'); // ['single', 'married']
            $table->integer('age');
            $table->string('dependent')->nullable();
            $table->string('self_employment'); // 'yes', 'no'
            $table->string('skills_training');
            $table->string('is_pwd'); // 'yes', 'no'
            $table->string('is_senior_citizen'); // 'yes', 'no'
            $table->string('spouse_first_name')->nullable();
            $table->string('spouse_middle_name')->nullable();
            $table->string('spouse_last_name')->nullable();
            $table->string('spouse_extension_name')->nullable();
            $table->timestamps(); //created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficiaries');
    }
};
