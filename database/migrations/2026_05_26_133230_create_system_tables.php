<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 2. Vendors
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        // 3. Assets
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_code')->unique();
            $table->string('asset_name');
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
            $table->date('purchase_date');
            $table->decimal('purchase_cost', 15, 2);
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
            $table->enum('condition', ['new', 'good', 'fair', 'poor', 'disposed'])->default('good');
            $table->enum('status', ['active', 'maintenance', 'disposed'])->default('active');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->date('warranty_expiry')->nullable();
            $table->timestamps();
        });

        // 4. Maintenance Records
        Schema::create('maintenance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();
            $table->date('maintenance_date');
            $table->decimal('cost', 15, 2);
            $table->text('description');
            $table->string('performed_by');
            $table->timestamps();
        });

        // 5. Procurements
        Schema::create('procurements', function (Blueprint $table) {
            $table->id();
            $table->string('procurement_code')->unique();
            $table->string('title');
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
            $table->decimal('budget_allocated', 15, 2);
            $table->decimal('actual_cost', 15, 2)->nullable();
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
            $table->enum('status', ['pending', 'approved', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->foreignId('initiated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('start_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->timestamps();
        });

        // 6. Projects
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_name');
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
            $table->enum('project_status', ['planned', 'ongoing', 'completed', 'on_hold', 'cancelled'])->default('planned');
            $table->decimal('allocated_budget', 15, 2);
            $table->decimal('actual_spending', 15, 2)->default(0.00);
            $table->date('start_date');
            $table->date('expected_completion');
            $table->date('completion_date')->nullable();
            $table->integer('progress_percentage')->default(0);
            $table->timestamps();
        });

        // 7. Audit Logs
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->text('description');
            $table->string('ip_address')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // 8. System Settings
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('procurements');
        Schema::dropIfExists('maintenance_records');
        Schema::dropIfExists('assets');
        Schema::dropIfExists('vendors');
        Schema::dropIfExists('categories');
    }
};
