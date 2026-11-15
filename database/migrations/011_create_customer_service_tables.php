<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // سجل المكالمات
        Schema::create('calls', function (Blueprint $table) {
            $table->id();
            $table->string('call_code')->unique();
            $table->foreignId('patient_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('handled_by')->constrained('users');
            $table->string('caller_name')->nullable();
            $table->string('caller_phone');
            $table->enum('type', ['incoming', 'outgoing'])->default('incoming');
            $table->enum('purpose', ['appointment', 'inquiry', 'complaint', 'followup', 'other']);
            $table->enum('status', ['answered', 'missed', 'callback_needed', 'resolved'])->default('answered');
            $table->text('notes')->nullable();
            $table->integer('duration_seconds')->nullable();
            $table->timestamp('called_at');
            $table->timestamps();
        });

        // طلبات المتابعة
        Schema::create('followups', function (Blueprint $table) {
            $table->id();
            $table->string('followup_code')->unique();
            $table->foreignId('patient_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('call_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('assigned_to')->constrained('users');
            $table->foreignId('created_by')->constrained('users');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['appointment_reminder', 'post_treatment', 'complaint', 'lab_results', 'other']);
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->dateTime('due_date');
            $table->text('resolution_notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // تقييمات خدمة العملاء
        Schema::create('customer_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');
            $table->tinyInteger('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_ratings');
        Schema::dropIfExists('followups');
        Schema::dropIfExists('calls');
    }
};
