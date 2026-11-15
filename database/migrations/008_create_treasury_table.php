<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('treasury_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique();
            $table->foreignId('created_by')->constrained('users');
            $table->enum('type', ['income', 'expense']);
            $table->string('category'); // appointment_fee, medicine_sale, salary, rent, utilities
            $table->decimal('amount', 12, 2);
            $table->string('payment_method')->default('cash'); // cash, card, transfer
            $table->text('description')->nullable();
            $table->nullableMorphs('related');
            $table->date('transaction_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treasury_transactions');
    }
};
