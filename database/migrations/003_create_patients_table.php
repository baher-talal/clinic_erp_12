<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('patient_code')->unique(); // PAT-0001
            $table->string('name');
            $table->string('national_id')->unique()->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->date('birth_date');
            $table->string('phone');
            $table->string('phone_emergency')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('blood_type')->nullable();
            $table->text('allergies')->nullable();
            $table->text('chronic_diseases')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
