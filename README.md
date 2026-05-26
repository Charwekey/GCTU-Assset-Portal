# GCTU Asset & Procurement Tracking System

An enterprise-grade asset registry, procurement lifecycle workflow, project spending tracker, and budget utilization monitoring portal designed for **Ghana Communication Technology University (GCTU)**.

---

## 💻 Tech Stack

* **Backend**: Laravel 13 & PHP 8.3
* **Database**: SQLite (local development) / MySQL (production)
* **Frontend**: Tailwind CSS v4 & Blade templating (with Alpine.js)
* **Authentication**: Laravel Breeze
* **Authorization**: Laravel Policies & Gates (RBAC)
* **Bundler**: Vite

---

## 🛠️ Core Modules & Features

### 1. User Authentication & Role Management
* Strong encryption with custom attributes for `role` and `department_id`.
* Scoped access levels for **Admin**, **Department Manager**, **Officer**, and **Auditor**.

### 2. Asset Management Registry
* Register university assets with fields: Code, Category, Department, Cost, Vendor, Warranty, and Condition.
* Log asset movement and filter by condition (New, Good, Fair, Poor, Disposed) and status (Active, Maintenance, Disposed).
* Dedicated asset profile page featuring total and historical **Maintenance Logs**.

### 3. Procurement Workflow Lifecycle
* Create procurement records mapped to departments and vendors.
* Strict state transitions: `Pending` ➔ `Approved` ➔ `In Progress` ➔ `Completed` / `Cancelled`.
* **Auto-Asset Registration**: Completing a procurement allows a single-click registration of the item as an active asset in the GCTU registry.

### 4. Project Expenditure & Progress Tracking
* Create projects with allocated budgets and expected completion timelines.
* Interactive progress sliders and spending logs for Officers and Managers.
* Visual progress bars in list views.

### 5. Departmental Budget & Safety Limits
* Real-time budget headroom calculations (Committed Spend = Project Spending + Approved Procurements).
* Warning alerts if a pending procurement request overruns the department's budget cap limit.
* Colorful progress indicators on the dashboard summarizing budget utilization.

### 6. Reports & CSV Exports
* Download spreadsheet exports with dynamic timestamps for all Assets, Procurements, and Projects.

### 7. Interactive Analytics Dashboard
* Key Performance Indicators (KPIs) showing asset counts, active projects, and pending approvals.
* Custom **Chart.js** doughnut charts showing condition breakdowns.
* Activity feeds and maintenance alert lists.

### 8. Audit Logs
* Track every user registration, modification, deletion, approval, and cancellation with IP logs, timestamps, and description records.

---

## 🔑 Test Login Credentials

To test the system locally, run migrations and seed database, then login at `http://gctu-assset-portal.test/login` (or localhost address) with:

| Role | Email | Password | Scope / Permissions |
| :--- | :--- | :--- | :--- |
| **System Administrator** | `admin@gctu.edu.gh` | `password` | Global read/write, admin settings, full audit trail. |
| **Department Manager** | `manager@gctu.edu.gh` | `password` | Manage CS & IT department assets, projects, and approvals. |
| **Department Officer** | `officer@gctu.edu.gh` | `password` | Log maintenance, request procurements, update project progress. |
| **Internal Auditor** | `auditor@gctu.edu.gh` | `password` | Read-only access to all modules, settings, and audit logs. |

---

## 🚀 Local Installation & Setup

1. **Clone the repository** and change directory.
2. **Setup environment variables**:
   ```bash
   copy .env.example .env
   ```
3. **Install composer packages**:
   ```bash
   composer install
   ```
4. **Clean-install node modules & compile**:
   ```bash
   npm install
   npm run build
   ```
5. **Fresh migrate and seed the database**:
   ```bash
   php artisan migrate:fresh --seed
   ```
6. **Generate application key**:
   ```bash
   php artisan key:generate
   ```
7. **Serve the application**:
   ```bash
   php artisan serve
   ```
   Or access via Herd/Laragon local domain: `http://gctu-assset-portal.test`.
