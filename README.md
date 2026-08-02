<div align="center">
  <img src="public/favicon.svg" alt="Creative Starter Logo" width="108" height="108" />

  # 🚀 Laravel Vue Creative Dashboard Bundle (PRO)
  ### State-of-the-Art Enterprise SaaS Dashboard & Integrated CRUD Generator Bundle
  *Crafted with Laravel 13, Vue 3 (Composition API), Inertia v3, Tailwind CSS v4, TypeScript, Spatie RBAC & Creative Vue CRUD Studio v5.0*

  <br />

  [![Laravel 13](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
  [![Vue 3](https://img.shields.io/badge/Vue.js-3.x-4FC08D?style=for-the-badge&logo=vuedotjs&logoColor=white)](https://vuejs.org)
  [![Inertia.js v3](https://img.shields.io/badge/Inertia.js-v3.0-9553E9?style=for-the-badge&logo=inertia&logoColor=white)](https://inertiajs.com)
  [![Tailwind CSS v4](https://img.shields.io/badge/Tailwind_CSS-v4.0-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
  [![TypeScript](https://img.shields.io/badge/TypeScript-5.x-3178C6?style=for-the-badge&logo=typescript&logoColor=white)](https://www.typescriptlang.org)
  [![CRUD Studio v5.0](https://img.shields.io/badge/CRUD_Studio-v5.0-6366F1?style=for-the-badge&logo=laravel&logoColor=white)](https://github.com/mr-creative-hmh/creative-vue-crud-studio-)
  [![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg?style=for-the-badge)](LICENSE.md)

</div>

---

## 🌟 About The PRO Bundle

The **Laravel Vue Creative Dashboard Bundle** is a complete, production-ready SaaS starter kit and visual CRUD generation suite. It combines the **Creative Starter Dashboard Kit** with the powerful **Creative Vue CRUD Studio (v5.0)** natively pre-installed.

It enables developers and teams to launch modern, bi-directional (**English & Arabic RTL**) web applications with complete **Spatie RBAC**, audit trails with 1-click undo, user impersonation, and a visual Web UI + CLI generator for full-stack Vue 3 Inertia CRUD modules in seconds.

> **Author:** Eng. Hasan Mohammad Hasan (م. حسن محمد حسن)

---

## 🖼️ Visual Showcase & Dashboard Previews

### 💻 Enterprise LTR Dashboard Overview
![Enterprise LTR Dashboard Overview](docs/assets/dashboard-overview.png)

### 🌐 Native Bi-Directional Arabic (RTL) Dashboard
![Native Bi-Directional Arabic RTL Dashboard](docs/assets/dashboard-arabic.png)

### 👥 User Management & Impersonation
![User Management & Role Assignment](docs/assets/users-management.png)

### 👑 Spatie Roles & Granular Permissions Matrix
![Spatie Roles & Granular Permissions Matrix](docs/assets/roles-permissions.png)

### 📜 Real-Time Audit Trails & 1-Click Reversal
![Real-Time Audit Trails & Activity Logs](docs/assets/activity-logs.png)

---

## ✨ Bundle Core Features

### 🛡️ Dashboard Foundation
- **Authentication & Security**: Powered by Laravel Fortify. Supports Passkeys (WebAuthn), Two-Factor Authentication (2FA) with QR codes, session management, and rate limiting.
- **User Impersonation**: 1-Click "Log in as User" for Super Admins with sticky status banner and audit log tracking.
- **Spatie RBAC & Authorization**: Granular role-based permissions matrix, custom permission generators, and reactive `useAuth()` composable.
- **Audit Trails & 1-Click Undo**: Complete activity logging with JSON diff visualizer and 1-click state reversal for created and updated models.
- **System Settings Management**: Update app name, support email, default language, and trigger application-wide **Maintenance Mode**.
- **Sanctum API Tokens**: Issue, manage, and revoke API access tokens with custom scopes.
- **Notifications Center**: Real-time unread badge counters, slide-out drawer, and full management page.
- **i18n & Bi-Directional RTL**: Seamless switching between **English (LTR)** and **Arabic (RTL)** with layout synchronization.
- **Theme & Color Swatches**: Light/Dark mode with dynamic accent color swatches (Indigo, Emerald, Violet, Rose, Amber, Slate).

### ⚡ Creative Vue CRUD Studio (v5.0 PRO Engine)
- **🎛️ 4-Tab Web UI Dashboard (`/crud-studio`)**: Zero-clutter visual builder for creating full-stack CRUD stacks visually.
- **🔐 Integrated License Manager**: Includes built-in license key management and verification.
- **📦 1-Click Domain Presets**: Instant generators for E-Commerce Products, Orders, Blog Posts, Support Tickets, Projects, CRM Customers, Courses, and Patients.
- **📜 Schema Revision History & 1-Click Rollback**: View schema version history and restore past database schemas with 1 click.
- **📱 Smart Field Controls & Status Badges**: Automatic mapping for Color Pickers, WYSIWYG Editors, iOS Toggles, Currency Inputs, Date Pickers, and Single/Multi File Uploads.
- **🔗 Eloquent Relationship Builder**: Visual builder for `belongsTo`, `hasMany`, and `belongsToMany` relationships with auto-populated select dropdowns.
- **📊 View Architectures & Layouts**:
  - `dynamic`: Dynamic View Switcher (Table, Grid Cards & Compact List).
  - `table`: High-density Data Table view.
  - `grid`: Media Grid Cards with hover zoom & status badges.
  - `compact`: Minimalist dense list view.
  - `single_card` & `two_columns`: Flexible form layout options.
- **📥 1-Click CSV Export**: Streaming memory-efficient CSV export endpoint.

---

## 🛠️ Technology Stack

| Layer | Technology |
| :--- | :--- |
| **Backend Framework** | Laravel 13.x (PHP 8.3+) |
| **Frontend Framework** | Vue 3 (Composition API, `<script setup>`) |
| **SPA Bridge** | Inertia.js v3.0 |
| **CSS & Design System** | Tailwind CSS v4.0 + Radix Vue / shadcn-vue |
| **Language & Typing** | TypeScript 5.x + Vue-TSC |
| **CRUD Generator** | Creative Vue CRUD Studio v5.0 |
| **Security & Auth** | Laravel Fortify, Sanctum, WebAuthn Passkeys |
| **Permissions** | Spatie Laravel-Permission |
| **Activity Auditing** | Spatie Laravel-Activitylog |
| **Analytics & Charts** | ApexCharts + Vue3-ApexCharts |
| **Toast Alerts** | Vue-Sonner |
| **Testing Suite** | Pest PHP 4.x |

---

## 🚀 Quick Start & Installation

### Prerequisites
- **PHP** >= 8.3
- **Composer** >= 2.x
- **Node.js** >= 20.x & **NPM**

### Step-by-Step Setup

```bash
# 1. Clone the repository
git clone https://github.com/mr-creative-hmh/laravel-vue-creative-dashboard-bundle.git
cd laravel-vue-creative-dashboard-bundle

# 2. Install PHP Dependencies
composer install

# 3. Install Node.js Frontend Dependencies
npm install

# 4. Configure Environment File
cp .env.example .env
php artisan key:generate

# 5. Create Storage Link for Avatar & File Uploads
php artisan storage:link

# 6. Run Database Migrations & Seed Default Accounts
php artisan migrate:fresh --seed

# 7. Install Creative Vue CRUD Studio Assets & Config
php artisan crud-studio:install

# 8. Start Local Development Server (Artisan Serve + Vite)
composer run dev
```

Visit the application at **`http://localhost:8000`** in your browser.

---

## 🔑 Default Accounts & Access Levels

After database seeding (`php artisan migrate:fresh --seed`), test accounts are available:

| Role | Email | Password | Access Level |
| :--- | :--- | :--- | :--- |
| **Super Admin** | `admin@example.com` | `password` | Unrestricted System Access |
| **Admin** | `manager.admin@example.com` | `password` | User & System Management |
| **Manager** | `manager@example.com` | `password` | Users & Audit Trail Reversals |
| **User** | `user@example.com` | `password` | Standard Dashboard End-User |

---

## 🧰 Artisan Commands Reference

```bash
# Launch Interactive CLI CRUD Studio Generator
php artisan crud-studio:make

# Display CRUD Studio Help Guide
php artisan crud-studio:help

# Re-publish CRUD Studio Assets & Configuration
php artisan crud-studio:install

# Generate CRUD Permissions for a Specific Model (e.g., Product)
php artisan permissions:generate Product

# Automatically Scan app/Models/ and Sync Missing Permissions
php artisan permissions:sync
```

---

## 🧪 Code Quality & Automated Tests

```bash
# Run Pest PHP Test Suite
php artisan test

# Check Code Formatting (Laravel Pint)
vendor/bin/pint --test

# Automatically Fix Code Formatting
vendor/bin/pint

# Run Vue/TypeScript Type Checker
npm run types:check

# Compile Production Frontend Assets
npm run build
```

---

## 📄 License & Attribution

This project is open-sourced under the [MIT License](LICENSE.md).

Designed and developed with ❤️ by **Eng. Hasan Mohammad Hasan** (م. حسن محمد حسن).
