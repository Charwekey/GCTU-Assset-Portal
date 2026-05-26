<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Department;
use App\Models\MaintenanceRecord;
use App\Models\Procurement;
use App\Models\Project;
use App\Models\SystemSetting;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. System Settings
        SystemSetting::set('app_name', 'GCTU Asset Hub');
        SystemSetting::set('currency', 'USD');
        SystemSetting::set('maintenance_warning_days', '30');

        // 2. Departments
        $deptCS = Department::create([
            'name' => 'Computer Science & IT',
            'code' => 'CS-IT',
            'budget_limit' => 150000.00,
        ]);

        $deptFinance = Department::create([
            'name' => 'Finance & Accounting',
            'code' => 'FIN',
            'budget_limit' => 80000.00,
        ]);

        $deptHR = Department::create([
            'name' => 'Human Resources',
            'code' => 'HR',
            'budget_limit' => 50000.00,
        ]);

        $deptEng = Department::create([
            'name' => 'Engineering Faculty',
            'code' => 'ENG',
            'budget_limit' => 250000.00,
        ]);

        $deptRegistry = Department::create([
            'name' => 'University Registry',
            'code' => 'REG',
            'budget_limit' => 60000.00,
        ]);

        // 3. Categories
        $catIT = Category::create(['name' => 'IT Hardware', 'description' => 'Computers, laptops, servers, and monitors.']);
        $catFurniture = Category::create(['name' => 'Office Furniture', 'description' => 'Chairs, desks, filing cabinets, and conference tables.']);
        $catLab = Category::create(['name' => 'Lab Equipment', 'description' => 'Scientific and engineering laboratory apparatus.']);
        $catVehicles = Category::create(['name' => 'Vehicles', 'description' => 'Institutional cars, shuttles, and maintenance trucks.']);
        $catSoftware = Category::create(['name' => 'Software Licenses', 'description' => 'OS, IDEs, and office productivity suite license keys.']);

        // 4. Vendors
        $vendorDell = Vendor::create(['name' => 'Dell Technologies', 'email' => 'institutional-sales@dell.com', 'phone' => '+1-800-456-3355', 'address' => 'One Dell Way, Round Rock, TX']);
        $vendorHerman = Vendor::create(['name' => 'Herman Miller', 'email' => 'support@hermanmiller.com', 'phone' => '+1-888-443-4357', 'address' => '855 East Main Ave, Zeeland, MI']);
        $vendorToyota = Vendor::create(['name' => 'Toyota GSA Ltd', 'email' => 'fleet@toyota.com.gh', 'phone' => '+233-302-221000', 'address' => 'Graphic Road, Accra, Ghana']);
        $vendorMS = Vendor::create(['name' => 'Microsoft West Africa', 'email' => 'edu-licensing@microsoft.com', 'phone' => '+233-216-70000', 'address' => 'Heritage Tower, Accra']);

        // 5. Users
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@gctu.edu.gh',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'department_id' => null,
        ]);

        $manager = User::create([
            'name' => 'Dr. Kwame Nkrumah',
            'email' => 'manager@gctu.edu.gh',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'department_id' => $deptCS->id,
        ]);

        $officer = User::create([
            'name' => 'Kofi Mensah',
            'email' => 'officer@gctu.edu.gh',
            'password' => Hash::make('password'),
            'role' => 'officer',
            'department_id' => $deptCS->id,
        ]);

        $auditor = User::create([
            'name' => 'Sarah Johnson',
            'email' => 'auditor@gctu.edu.gh',
            'password' => Hash::make('password'),
            'role' => 'auditor',
            'department_id' => null,
        ]);

        $managerEng = User::create([
            'name' => 'Prof. Ama Serwaa',
            'email' => 'manager.eng@gctu.edu.gh',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'department_id' => $deptEng->id,
        ]);

        // 6. Assets
        $asset1 = Asset::create([
            'asset_code' => 'AST-CS-001',
            'asset_name' => 'PowerEdge R750 Server',
            'category_id' => $catIT->id,
            'department_id' => $deptCS->id,
            'purchase_date' => '2025-01-15',
            'purchase_cost' => 12500.00,
            'vendor_id' => $vendorDell->id,
            'condition' => 'good',
            'status' => 'active',
            'assigned_to' => $officer->id,
            'warranty_expiry' => '2028-01-15',
        ]);

        $asset2 = Asset::create([
            'asset_code' => 'AST-CS-002',
            'asset_name' => 'Aeron Ergonomic Chair',
            'category_id' => $catFurniture->id,
            'department_id' => $deptCS->id,
            'purchase_date' => '2025-03-10',
            'purchase_cost' => 1400.00,
            'vendor_id' => $vendorHerman->id,
            'condition' => 'new',
            'status' => 'active',
            'assigned_to' => $manager->id,
            'warranty_expiry' => '2037-03-10',
        ]);

        $asset3 = Asset::create([
            'asset_code' => 'AST-ENG-001',
            'asset_name' => 'Oscilloscope 100MHz',
            'category_id' => $catLab->id,
            'department_id' => $deptEng->id,
            'purchase_date' => '2024-11-20',
            'purchase_cost' => 3500.00,
            'vendor_id' => null,
            'condition' => 'fair',
            'status' => 'maintenance',
            'assigned_to' => null,
            'warranty_expiry' => '2025-11-20',
        ]);

        $asset4 = Asset::create([
            'asset_code' => 'AST-REG-001',
            'asset_name' => 'Toyota Hilux double cabin',
            'category_id' => $catVehicles->id,
            'department_id' => $deptRegistry->id,
            'purchase_date' => '2023-06-01',
            'purchase_cost' => 45000.00,
            'vendor_id' => $vendorToyota->id,
            'condition' => 'good',
            'status' => 'active',
            'assigned_to' => null,
            'warranty_expiry' => '2026-06-01',
        ]);

        // 7. Maintenance Records
        MaintenanceRecord::create([
            'asset_id' => $asset3->id,
            'maintenance_date' => '2026-04-15',
            'cost' => 250.00,
            'description' => 'Recalibrated signal sensor and replaced power supply capacitor.',
            'performed_by' => 'Accra Labs Calibration Services',
        ]);

        MaintenanceRecord::create([
            'asset_id' => $asset4->id,
            'maintenance_date' => '2025-12-10',
            'cost' => 650.00,
            'description' => 'Routine 50,000 km oil service, brake pads replacement, and tire rotation.',
            'performed_by' => 'Toyota Ghana Service Center',
        ]);

        // 8. Procurements
        Procurement::create([
            'procurement_code' => 'PRC-2026-001',
            'title' => '25 Dell Latitude 5440 Laptops',
            'department_id' => $deptCS->id,
            'budget_allocated' => 30000.00,
            'actual_cost' => 28750.00,
            'vendor_id' => $vendorDell->id,
            'status' => 'completed',
            'initiated_by' => $officer->id,
            'approved_by' => $manager->id,
            'start_date' => '2026-02-10',
            'completion_date' => '2026-03-05',
        ]);

        Procurement::create([
            'procurement_code' => 'PRC-2026-002',
            'title' => 'Microsoft 365 Enterprise Renewal',
            'department_id' => $deptFinance->id,
            'budget_allocated' => 15000.00,
            'actual_cost' => null,
            'vendor_id' => $vendorMS->id,
            'status' => 'approved',
            'initiated_by' => $admin->id,
            'approved_by' => $admin->id,
            'start_date' => '2026-05-01',
            'completion_date' => null,
        ]);

        Procurement::create([
            'procurement_code' => 'PRC-2026-003',
            'title' => 'Lab Upgrade Workstations',
            'department_id' => $deptCS->id,
            'budget_allocated' => 45000.00,
            'actual_cost' => null,
            'vendor_id' => $vendorDell->id,
            'status' => 'pending',
            'initiated_by' => $officer->id,
            'approved_by' => null,
            'start_date' => null,
            'completion_date' => null,
        ]);

        Procurement::create([
            'procurement_code' => 'PRC-2026-004',
            'title' => 'Engineering Seminar Hall Furniture',
            'department_id' => $deptEng->id,
            'budget_allocated' => 75000.00,
            'actual_cost' => null,
            'vendor_id' => $vendorHerman->id,
            'status' => 'in_progress',
            'initiated_by' => $managerEng->id,
            'approved_by' => $admin->id,
            'start_date' => '2026-05-10',
            'completion_date' => null,
        ]);

        // 9. Projects
        Project::create([
            'project_name' => 'Campus Wi-Fi expansion',
            'department_id' => $deptCS->id,
            'project_status' => 'ongoing',
            'allocated_budget' => 60000.00,
            'actual_spending' => 38000.00,
            'start_date' => '2026-01-10',
            'expected_completion' => '2026-08-30',
            'completion_date' => null,
            'progress_percentage' => 65,
        ]);

        Project::create([
            'project_name' => 'Solar Power Grid for Lab 4',
            'department_id' => $deptEng->id,
            'project_status' => 'planned',
            'allocated_budget' => 120000.00,
            'actual_spending' => 0.00,
            'start_date' => '2026-06-15',
            'expected_completion' => '2026-12-15',
            'completion_date' => null,
            'progress_percentage' => 0,
        ]);

        Project::create([
            'project_name' => 'Digitization of Academic Archives',
            'department_id' => $deptRegistry->id,
            'project_status' => 'completed',
            'allocated_budget' => 25000.00,
            'actual_spending' => 24500.00,
            'start_date' => '2025-06-01',
            'expected_completion' => '2026-03-01',
            'completion_date' => '2026-02-28',
            'progress_percentage' => 100,
        ]);
    }
}
