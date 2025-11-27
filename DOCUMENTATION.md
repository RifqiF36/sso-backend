# 🔐 SSO Backend - Dokumentasi Lengkap

## 📋 Daftar Isi
- [Tentang Project](#tentang-project)
- [Fitur Utama](#fitur-utama)
- [Teknologi](#teknologi)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi](#instalasi)
- [Konfigurasi](#konfigurasi)
- [Struktur Database](#struktur-database)
- [Struktur Project](#struktur-project)
- [API Documentation](#api-documentation)
- [Autentikasi & Authorization](#autentikasi--authorization)
- [SSO Flow](#sso-flow)
- [Multi-Tenancy](#multi-tenancy)
- [Role Management](#role-management)
- [Testing](#testing)
- [Deployment](#deployment)
- [Troubleshooting](#troubleshooting)
- [Best Practices](#best-practices)

---

## 📖 Tentang Project

**SSO Backend** adalah sistem Single Sign-On (SSO) yang dibangun dengan Laravel 12 yang berfungsi sebagai Identity Provider (IdP) untuk berbagai aplikasi terintegrasi. Sistem ini memungkinkan pengguna untuk login sekali dan mengakses berbagai aplikasi tanpa perlu login ulang.

### Use Case Utama:
- **Central Authentication** untuk multiple aplikasi (SIPRIMA, Service Desk, Change Management)
- **OAuth2 Authorization Code Grant Flow** sebagai Identity Provider
- **Role-based Access Control (RBAC)** dengan multi-tenancy
- **Audit Logging** untuk tracking aktivitas IAM
- **User Management** dan profile management

### Aplikasi Terintegrasi:
1. **SIPRIMA** - Asset Management System
2. **Service Desk** - IT Service Management
3. **Change Management** - Change & Configuration Management

---

## ✨ Fitur Utama

### 1. **Authentication & Authorization**
- ✅ Login/Logout dengan Laravel Sanctum (Token-based)
- ✅ OAuth2 Authorization Code Grant Flow untuk SSO
- ✅ Role-based access control (RBAC)
- ✅ Multi-tenancy support (per OPD/Dinas)
- ✅ Password management (change password)
- ✅ Profile management

### 2. **SSO Integration**
- ✅ Authorization endpoint untuk aplikasi klien
- ✅ Token exchange endpoint
- ✅ UserInfo endpoint untuk mendapatkan data user
- ✅ Support multiple client applications
- ✅ OAuth2 standard compliance

### 3. **Identity & Access Management (IAM)**
- ✅ Role management per module & tenant
- ✅ Assign/Revoke roles to users
- ✅ Audit logging untuk tracking perubahan
- ✅ User roles tracking per tenant
- ✅ Permission-based access control

### 4. **Multi-Tenancy**
- ✅ Support multiple tenants (OPD/Dinas)
- ✅ Switch tenant functionality
- ✅ Isolated data per tenant
- ✅ Tenant-based role assignment
- ✅ Tenant status management

### 5. **App Picker**
- ✅ Dynamic application list berdasarkan role user
- ✅ Konfigurasi URL aplikasi per environment
- ✅ Role-based dashboard routing
- ✅ Application icon & description

### 6. **User Management**
- ✅ Create user account (staff only)
- ✅ User profile builder dengan tenant assignment
- ✅ Soft deletes untuk data integrity
- ✅ User profile with complete information (NIP, jabatan, dll)

---

## 🛠 Teknologi

### Backend
- **Framework:** Laravel 12.x
- **PHP:** ^8.2
- **Authentication:** Laravel Sanctum (Token-based)
- **API Documentation:** Swagger/OpenAPI 3.0 (L5-Swagger)

### Database
- **Default:** SQLite (Development)
- **Production:** MySQL/PostgreSQL (Configurable)
- **ORM:** Eloquent
- **Migrations:** Database version control

### Frontend Assets
- **Build Tool:** Vite 7.x
- **CSS Framework:** Tailwind CSS 4.x
- **HTTP Client:** Axios

### Development Tools
- **Testing:** PHPUnit 11.x
- **Code Style:** Laravel Pint
- **Containerization:** Laravel Sail (Docker)
- **Process Management:** Concurrently
- **Logging:** Laravel Pail

---

## 💻 Persyaratan Sistem

### Minimum Requirements:
- **PHP:** >= 8.2
- **Composer:** >= 2.x
- **Node.js:** >= 18.x
- **NPM:** >= 9.x
- **Database:** SQLite / MySQL 8.x / PostgreSQL 13.x
- **Memory:** 2GB RAM minimum
- **Storage:** SSD recommended

### Recommended:
- **PHP:** 8.3
- **MySQL:** 8.0 atau PostgreSQL 15
- **Redis:** untuk caching dan queue
- **Nginx/Apache:** untuk production

---

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/RifqiF36/sso-backend.git
cd sso-backend
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup

**Untuk SQLite (Development):**
```bash
# Database SQLite sudah dikonfigurasi secara default
php artisan migrate --seed
```

**Untuk MySQL (Production):**
```bash
# Edit .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sso_backend
DB_USERNAME=root
DB_PASSWORD=your_password

# Run migrations
php artisan migrate --seed
```

### 5. Build Assets

```bash
npm run build
```

### 6. Generate Swagger Documentation

```bash
php artisan l5-swagger:generate
```

### 7. Run Development Server

**Option A: Single Command (Recommended)**
```bash
composer dev
```
Ini akan menjalankan:
- Laravel server (port 8000)
- Queue worker
- Log monitoring (Laravel Pail)
- Vite dev server

**Option B: Manual**
```bash
php artisan serve --port=9000
```

### 8. Akses Aplikasi

- **API Base URL:** `http://localhost:9000/api/v1`
- **Swagger UI:** `http://localhost:9000/api/documentation`

---

## ⚙️ Konfigurasi

### Environment Variables

Edit file `.env` untuk konfigurasi:

#### Database
```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite

# Atau untuk MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sso_backend
DB_USERNAME=root
DB_PASSWORD=your_password
```

#### Application
```env
APP_NAME="SSO Backend"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:9000
APP_TIMEZONE=Asia/Jakarta
```

#### Sanctum (API Authentication)
```env
SANCTUM_STATEFUL_DOMAINS=localhost:9000,127.0.0.1:9000
SESSION_DOMAIN=localhost
SESSION_DRIVER=database
```

#### Swagger
```env
L5_SWAGGER_CONST_HOST=http://127.0.0.1:9000
L5_SWAGGER_GENERATE_ALWAYS=true
```

#### SSO Client Applications
```env
# SIPRIMA - Asset Management
APP_ASSET_URL=http://127.0.0.1:8000
APP_ASSET_URL_STAFF=http://localhost:5401/
APP_ASSET_URL_AUDITOR=http://localhost:5404/
APP_ASSET_URL_DISKOMINFO=http://localhost:5403/
APP_ASSET_URL_KEPALA_SEKSI=http://localhost:5402/

# Service Desk
APP_SERVICE_DESK_URL=http://127.0.0.1:8000
APP_SERVICE_DESK_URL_STAFF=http://127.0.0.1:8000
APP_SERVICE_DESK_URL_ADMIN=http://127.0.0.1:8000

# Change Management
APP_CHANGE_URL=http://127.0.0.1:8000
APP_CHANGE_URL_STAFF=http://127.0.0.1:8000

# Gunakan URL lokal atau remote
SSO_USE_LOCAL_APP_URLS=true
```

#### Logging
```env
LOG_CHANNEL=stack
LOG_LEVEL=debug
```

#### Queue
```env
QUEUE_CONNECTION=database
```

---

## 🗄️ Struktur Database

### Tabel Utama

#### 1. **users**
User utama sistem SSO
```sql
- id (PK, bigint, auto_increment)
- name (varchar)
- email (varchar, unique)
- password (varchar)
- remember_token (varchar, nullable)
- created_at (timestamp)
- updated_at (timestamp)
- deleted_at (timestamp, nullable)
```

#### 2. **user_profiles**
Profil lengkap user dengan tenant assignment
```sql
- id (PK, bigint, auto_increment)
- user_id (FK -> users.id)
- tenant_id (FK -> tenants.tenant_id)
- nip (varchar, nullable)
- jabatan (varchar, nullable)
- nomor_telepon (varchar, nullable)
- alamat (text, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

#### 3. **tenants**
Organisasi/OPD/Dinas
```sql
- tenant_id (PK, bigint, auto_increment)
- nama (varchar)
- kode (varchar, unique)
- status (enum: 'active', 'inactive', default: 'active')
- created_at (timestamp)
- updated_at (timestamp)
- deleted_at (timestamp, nullable)
```

#### 4. **roles**
Role dalam sistem per module
```sql
- role_id (PK, bigint, auto_increment)
- name (varchar)
- code (varchar)
- module (enum: 'asset', 'service', 'change')
- description (text, nullable)
- created_at (timestamp)
- updated_at (timestamp)

Unique: (code, module)
```

#### 5. **permissions**
Permission granular untuk roles
```sql
- permission_id (PK, bigint, auto_increment)
- name (varchar)
- code (varchar, unique)
- module (enum: 'asset', 'service', 'change')
- description (text, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

#### 6. **user_roles**
Assignment role ke user (multi-tenancy)
```sql
- id (PK, bigint, auto_increment)
- user_id (FK -> users.id)
- role_id (FK -> roles.role_id)
- tenant_id (FK -> tenants.tenant_id)
- module (enum: 'asset', 'service', 'change')
- created_at (timestamp)
- updated_at (timestamp)

Unique: (user_id, role_id, tenant_id, module)
```

#### 7. **sso_providers**
Konfigurasi OAuth2 client applications
```sql
- id (PK, bigint, auto_increment)
- client_id (varchar, unique)
- client_secret (varchar)
- name (varchar)
- allowed_redirect_uris (json)
- enabled (boolean, default: true)
- created_at (timestamp)
- updated_at (timestamp)
```

**Example Data:**
```json
{
  "client_id": "siprima-app",
  "client_secret": "supersecret123",
  "name": "SIPRIMA Asset Management",
  "allowed_redirect_uris": [
    "http://localhost:5401/auth/callback",
    "https://dinas-siprima.vercel.app/auth/callback"
  ],
  "enabled": true
}
```

#### 8. **sso_identities**
OAuth2 authorization codes & access tokens
```sql
- id (PK, bigint, auto_increment)
- user_id (FK -> users.id)
- provider_id (FK -> sso_providers.id)
- authorization_code (varchar, unique, nullable)
- access_token (varchar, unique, nullable)
- expires_at (timestamp, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

#### 9. **iam_audit_logs**
Audit trail untuk IAM operations
```sql
- id (PK, bigint, auto_increment)
- user_id (FK -> users.id) -- Target user
- actor_id (FK -> users.id) -- Who performed the action
- tenant_id (FK -> tenants.tenant_id, nullable)
- action (enum: 'assign_role', 'revoke_role')
- details (json, nullable)
- ip_address (varchar, nullable)
- created_at (timestamp)
```

**Example Audit Log:**
```json
{
  "action": "assign_role",
  "details": {
    "role_name": "Staff",
    "module": "asset",
    "tenant_name": "Dinas Kominfo"
  },
  "ip_address": "127.0.0.1"
}
```

### Entity Relationship Diagram

```
┌─────────┐
│  users  │
└────┬────┘
     │
     ├──1:1──┐
     │       ▼
     │  ┌──────────────┐
     │  │user_profiles │──┐
     │  └──────────────┘  │
     │                    │
     ├──1:N──┐            │
     │       ▼            │
     │  ┌───────────┐    │
     │  │user_roles │────┤
     │  └─────┬─────┘    │
     │        │          │
     │        ├──FK──┐   │
     │        │      ▼   │
     │        │  ┌───────┴───┐
     │        │  │  tenants  │
     │        │  └───────────┘
     │        │
     │        └──FK──┐
     │               ▼
     │          ┌────────┐
     │          │ roles  │
     │          └────────┘
     │
     ├──1:N──┐
     │       ▼
     │  ┌────────────────┐
     │  │sso_identities  │──┐
     │  └────────────────┘  │
     │                      │
     │                      └──FK──┐
     │                             ▼
     │                        ┌──────────────┐
     │                        │sso_providers │
     │                        └──────────────┘
     │
     └──1:N──┐
             ▼
        ┌───────────────┐
        │iam_audit_logs │
        └───────┬───────┘
                │
                └──FK── tenants
```

---

## 📂 Struktur Project

```
sso-backend/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AppSelectorController.php   # App picker logic
│   │       ├── AuthController.php          # Auth & SSO endpoints
│   │       ├── IamController.php           # IAM operations
│   │       ├── StaffUserController.php     # User creation
│   │       └── TenantController.php        # Tenant management
│   ├── Models/
│   │   ├── User.php                        # User model
│   │   ├── UserProfile.php                 # User profile
│   │   ├── Tenant.php                      # Tenant/OPD
│   │   ├── Role.php                        # Role model
│   │   ├── Permission.php                  # Permission model
│   │   ├── SsoProvider.php                 # OAuth2 clients
│   │   ├── SsoIdentity.php                 # OAuth2 tokens
│   │   └── IamAuditLog.php                 # Audit logs
│   ├── Services/
│   │   ├── AppCatalogService.php           # App picker service
│   │   ├── UserContextService.php          # User context helper
│   │   └── UserProfileBuilder.php          # Profile builder
│   └── Swagger/
│       ├── OpenApi.php                     # Main Swagger config
│       ├── AuthPaths.php                   # Auth endpoints docs
│       ├── TenantPaths.php                 # Tenant endpoints docs
│       ├── IamPaths.php                    # IAM endpoints docs
│       ├── AppPaths.php                    # App picker docs
│       └── StaffPaths.php                  # Staff endpoints docs
├── config/
│   ├── auth.php                            # Auth configuration
│   ├── database.php                        # Database config
│   ├── l5-swagger.php                      # Swagger config
│   ├── sanctum.php                         # Sanctum config
│   └── role_apps.php                       # App & role mapping
├── database/
│   ├── migrations/                         # Database migrations
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── IamQuickSeed.php                # Quick seed for demo
├── routes/
│   ├── api.php                             # API routes
│   └── web.php                             # Web routes
├── docs/
│   └── sso_mitra.postman_collection.json   # Postman collection
├── tests/
│   ├── Feature/                            # Feature tests
│   └── Unit/                               # Unit tests
├── .env.example                            # Environment template
├── composer.json                           # PHP dependencies
├── package.json                            # Node dependencies
├── README.md                               # Quick start guide
├── SWAGGER_GUIDE.md                        # Swagger usage guide
└── DOCUMENTATION.md                        # This file
```

---

## 📚 API Documentation

### Swagger UI

API documentation menggunakan Swagger/OpenAPI 3.0 tersedia di:

```
http://localhost:9000/api/documentation
```

**Generate ulang dokumentasi:**
```bash
php artisan l5-swagger:generate
```

**Panduan lengkap:** Lihat [SWAGGER_GUIDE.md](SWAGGER_GUIDE.md) untuk petunjuk detail penggunaan Swagger UI, autentikasi, dan OAuth2 flow.

### Postman Collection

Postman collection tersedia di folder `docs/`:
- `docs/sso_mitra.postman_collection.json` - Import file ini ke Postman untuk testing API

### Available Endpoints

#### **Auth** (Authentication Lokal)
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | `/api/v1/auth/login` | Login & get Sanctum token | ❌ |
| POST | `/api/v1/auth/logout` | Logout & revoke token | ✅ |
| GET | `/api/v1/auth/me` | Get current user profile | ✅ |
| GET | `/api/v1/auth/roles` | Get user's roles | ✅ |
| POST | `/api/v1/auth/refresh` | Refresh token info | ✅ |
| PUT | `/api/v1/auth/profile` | Update user profile | ✅ |
| POST | `/api/v1/auth/change-password` | Change password | ✅ |

#### **SSO** (OAuth2 Flow)
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/v1/auth/sso/authorize` | Get authorization code | ✅ |
| POST | `/api/v1/auth/sso/token` | Exchange code for token | ❌ |
| GET | `/api/v1/auth/sso/userinfo` | Get user info | ✅ (OAuth) |

#### **Tenant** (Multi-Tenancy)
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/v1/tenants` | List user's tenants | ✅ |
| POST | `/api/v1/tenants/{tenant}/switch` | Switch active tenant | ✅ |

#### **IAM** (Identity & Access Management)
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/v1/iam/roles` | List roles by module | ✅ |
| GET | `/api/v1/iam/users/{id}/roles` | Get user roles | ✅ |
| POST | `/api/v1/iam/assign-role` | Assign role to user | ✅ |
| POST | `/api/v1/iam/revoke-role` | Revoke role from user | ✅ |
| GET | `/api/v1/iam/audit-logs` | View audit logs | ✅ |

#### **Staff** (User Management)
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | `/api/v1/staff/users` | Create new user | ✅ (Staff) |

#### **App Picker** (Application Catalog)
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/v1/apps` | List accessible apps | ✅ |

---

## 🔐 Autentikasi & Authorization

### 1. Login Flow (Sanctum)

**Request:**
```http
POST /api/v1/auth/login
Content-Type: application/json

{
  "email": "admin_kota@sso",
  "password": "AdminKota@123"
}
```

**Response:**
```json
{
  "token": "1|randomtokenstring",
  "user": {
    "id": 1,
    "name": "Admin Kota",
    "email": "admin_kota@sso",
    "profile": {
      "nip": "123456789",
      "jabatan": "Administrator",
      "tenant": {
        "tenant_id": 1,
        "nama": "Kota Bandung",
        "kode": "kota"
      }
    }
  }
}
```

### 2. Using Bearer Token

Untuk endpoint yang memerlukan autentikasi, gunakan token di header:

```http
GET /api/v1/auth/me
Authorization: Bearer 1|randomtokenstring
```

### 3. Role-Based Authorization

Sistem menggunakan role-based access control:

**Roles Available:**
- `staff` - Staff dinas
- `admin` - Administrator
- `kepala_seksi` / `verifikator` - Kepala Seksi/Verifikator
- `kepala_bidang` - Kepala Bidang
- `kepala_dinas` - Kepala Dinas
- `teknisi` - Teknisi IT
- `diskominfo` - Diskominfo
- `auditor` - Auditor

**Modules:**
- `asset` - Asset Management (SIPRIMA)
- `service` - Service Desk
- `change` - Change Management

**Example: Check User Role**
```http
GET /api/v1/auth/roles
Authorization: Bearer {token}
```

**Response:**
```json
{
  "roles": [
    {
      "role_id": 1,
      "name": "Staff",
      "code": "staff",
      "module": "asset",
      "tenant": {
        "tenant_id": 1,
        "nama": "Dinas Kominfo"
      }
    }
  ]
}
```

---

## 🔄 SSO Flow

### OAuth2 Authorization Code Grant Flow

#### Step 1: Authorization Request

Client aplikasi redirect user ke:

```
GET /api/v1/auth/sso/authorize?
  client_id=siprima-app&
  redirect_uri=http://localhost:5401/auth/callback&
  response_type=code&
  state=random_state_string
```

**Parameters:**
- `client_id`: ID aplikasi klien (registered di `sso_providers`)
- `redirect_uri`: URL callback aplikasi klien
- `response_type`: Harus `code`
- `state`: Random string untuk CSRF protection

**User harus sudah login** (authenticated dengan Sanctum token)

**Response: Redirect ke `redirect_uri` dengan code:**
```
http://localhost:5401/auth/callback?
  code=generated_authorization_code&
  state=random_state_string
```

#### Step 2: Token Exchange

Client aplikasi menukar authorization code dengan access token:

```http
POST /api/v1/auth/sso/token
Content-Type: application/json

{
  "grant_type": "authorization_code",
  "client_id": "siprima-app",
  "client_secret": "supersecret123",
  "code": "generated_authorization_code",
  "redirect_uri": "http://localhost:5401/auth/callback"
}
```

**Response:**
```json
{
  "access_token": "generated_access_token",
  "token_type": "Bearer",
  "expires_in": 3600
}
```

#### Step 3: Get User Info

Client aplikasi menggunakan access token untuk mendapatkan info user:

```http
GET /api/v1/auth/sso/userinfo
Authorization: Bearer generated_access_token
```

**Response:**
```json
{
  "id": 1,
  "name": "Admin Kota",
  "email": "admin_kota@sso",
  "nip": "123456789",
  "jabatan": "Administrator",
  "tenant": {
    "tenant_id": 1,
    "nama": "Kota Bandung",
    "kode": "kota"
  },
  "roles": [
    {
      "role": "staff",
      "module": "asset"
    }
  ]
}
```

### Sequence Diagram

```
User          Client App       SSO Backend
 |                |                 |
 |-- Open App --->|                 |
 |                |-- Authorize --->|
 |                |   (Step 1)      |
 |<-------------- Redirect to SSO --|
 |                                  |
 |-- Login (if needed) ------------>|
 |<-- Authorization Code ----------|
 |                                  |
 |-- Code + redirect to app ------->|
 |                |                 |
 |                |-- Token Req --->|
 |                |   (Step 2)      |
 |                |<-- Access Token-|
 |                |                 |
 |                |-- UserInfo ---->|
 |                |   (Step 3)      |
 |                |<-- User Data ---|
 |                |                 |
 |<-- Logged In --|                 |
```

---

## 🏢 Multi-Tenancy

### Konsep Multi-Tenancy

Sistem mendukung multiple tenants (OPD/Dinas). Setiap user dapat:
- Memiliki akses ke multiple tenants
- Switch tenant aktif
- Memiliki role berbeda di setiap tenant

### Get User's Tenants

```http
GET /api/v1/tenants
Authorization: Bearer {token}
```

**Response:**
```json
{
  "tenants": [
    {
      "tenant_id": 1,
      "nama": "Dinas Kominfo",
      "kode": "kominfo",
      "status": "active"
    },
    {
      "tenant_id": 2,
      "nama": "Dinas Pendidikan",
      "kode": "pendidikan",
      "status": "active"
    }
  ]
}
```

### Switch Tenant

```http
POST /api/v1/tenants/2/switch
Authorization: Bearer {token}
```

**Response:**
```json
{
  "message": "Switched to tenant: Dinas Pendidikan",
  "tenant": {
    "tenant_id": 2,
    "nama": "Dinas Pendidikan",
    "kode": "pendidikan"
  }
}
```

---

## 👥 Role Management

### Get Available Roles

```http
GET /api/v1/iam/roles?module=asset
Authorization: Bearer {token}
```

**Response:**
```json
{
  "roles": [
    {
      "role_id": 1,
      "name": "Staff",
      "code": "staff",
      "module": "asset",
      "description": "Staff Dinas"
    },
    {
      "role_id": 2,
      "name": "Kepala Seksi",
      "code": "kepala_seksi",
      "module": "asset",
      "description": "Kepala Seksi/Verifikator"
    }
  ]
}
```

### Assign Role to User

```http
POST /api/v1/iam/assign-role
Authorization: Bearer {token}
Content-Type: application/json

{
  "user_id": 5,
  "role_id": 1,
  "tenant_id": 1,
  "module": "asset"
}
```

**Response:**
```json
{
  "message": "Role assigned successfully",
  "user_role": {
    "id": 10,
    "user_id": 5,
    "role_id": 1,
    "tenant_id": 1,
    "module": "asset"
  }
}
```

### Revoke Role from User

```http
POST /api/v1/iam/revoke-role
Authorization: Bearer {token}
Content-Type: application/json

{
  "user_id": 5,
  "role_id": 1,
  "tenant_id": 1,
  "module": "asset"
}
```

### View Audit Logs

```http
GET /api/v1/iam/audit-logs?limit=50
Authorization: Bearer {token}
```

**Response:**
```json
{
  "logs": [
    {
      "id": 1,
      "action": "assign_role",
      "user": {
        "id": 5,
        "name": "John Doe"
      },
      "actor": {
        "id": 1,
        "name": "Admin Kota"
      },
      "tenant": {
        "tenant_id": 1,
        "nama": "Dinas Kominfo"
      },
      "details": {
        "role_name": "Staff",
        "module": "asset"
      },
      "ip_address": "127.0.0.1",
      "created_at": "2025-11-27T10:30:00Z"
    }
  ]
}
```

---

## 🧪 Testing

### Run All Tests

```bash
php artisan test
```

### Run Specific Test

```bash
php artisan test --filter=AuthTest
```

### Run with Coverage

```bash
php artisan test --coverage
```

### Test Structure

```
tests/
├── Feature/
│   ├── AuthTest.php           # Auth endpoints
│   ├── SsoTest.php            # SSO flow
│   ├── TenantTest.php         # Tenant management
│   ├── IamTest.php            # IAM operations
│   └── AppPickerTest.php      # App catalog
└── Unit/
    ├── UserTest.php           # User model
    ├── RoleTest.php           # Role model
    └── ServiceTest.php        # Services
```

### Example Test

```php
public function test_user_can_login()
{
    $response = $this->postJson('/api/v1/auth/login', [
        'email' => 'admin_kota@sso',
        'password' => 'AdminKota@123',
    ]);

    $response->assertStatus(200)
             ->assertJsonStructure([
                 'token',
                 'user' => ['id', 'name', 'email']
             ]);
}
```

---

## 🚀 Deployment

### Production Setup

#### 1. Server Requirements

- PHP 8.2+, Composer
- MySQL 8.0 / PostgreSQL 13+
- Nginx / Apache
- SSL Certificate (HTTPS)
- Redis (optional, untuk caching)

#### 2. Environment Configuration

```bash
# Copy and edit .env
cp .env.example .env

# Set production values
APP_ENV=production
APP_DEBUG=false
APP_URL=https://sso.yourdomain.com

DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_DATABASE=sso_backend
DB_USERNAME=db-user
DB_PASSWORD=secure-password

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

#### 3. Optimize Application

```bash
# Install dependencies
composer install --optimize-autoloader --no-dev

# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Generate Swagger docs
php artisan l5-swagger:generate

# Run migrations
php artisan migrate --force
```

#### 4. Set Permissions

```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### 5. Setup Queue Worker

Create systemd service `/etc/systemd/system/sso-queue.service`:

```ini
[Unit]
Description=SSO Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /path/to/sso-backend/artisan queue:work --tries=3

[Install]
WantedBy=multi-user.target
```

Start service:
```bash
systemctl enable sso-queue
systemctl start sso-queue
```

#### 6. Setup Nginx

```nginx
server {
    listen 80;
    server_name sso.yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name sso.yourdomain.com;
    root /path/to/sso-backend/public;

    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

#### 7. Setup Cron (Scheduler)

Add to crontab:
```bash
* * * * * cd /path/to/sso-backend && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🔧 Troubleshooting

### 1. Swagger UI tidak muncul

**Solusi:**
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear

# Generate swagger docs
php artisan l5-swagger:generate

# Check route
php artisan route:list | grep documentation
```

### 2. Token tidak valid / Unauthorized

**Solusi:**
- Pastikan header format benar: `Authorization: Bearer {token}`
- Check token belum expired
- Pastikan `SANCTUM_STATEFUL_DOMAINS` di `.env` sudah benar
- Coba login ulang untuk mendapatkan token baru

### 3. Database connection error

**Solusi:**
```bash
# Check .env database config
# Test connection
php artisan migrate:status

# Clear config cache
php artisan config:clear
```

### 4. CORS Error

**Solusi:**
Edit `config/cors.php`:
```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_origins' => ['http://localhost:5401', 'https://yourdomain.com'],
'supports_credentials' => true,
```

### 5. OAuth2 redirect_uri mismatch

**Solusi:**
- Check `allowed_redirect_uris` di table `sso_providers`
- URL harus exact match termasuk protokol (http/https)
- Tidak boleh ada trailing slash jika tidak ada di config

### 6. Queue tidak berjalan

**Solusi:**
```bash
# Check queue connection
php artisan queue:failed

# Restart queue worker
php artisan queue:restart

# Process jobs manually
php artisan queue:work --once
```

---

## 💡 Best Practices

### 1. Security

- ✅ Selalu gunakan HTTPS di production
- ✅ Gunakan environment variables untuk sensitive data
- ✅ Rotate client_secret secara berkala
- ✅ Implement rate limiting untuk API endpoints
- ✅ Validate dan sanitize input
- ✅ Use prepared statements (Eloquent ORM)
- ✅ Keep dependencies updated

### 2. Performance

- ✅ Enable caching (Redis) di production
- ✅ Use eager loading untuk prevent N+1 queries
- ✅ Index foreign keys di database
- ✅ Optimize Swagger generation (disable auto-generate di production)
- ✅ Use queue untuk heavy operations
- ✅ Enable OPcache untuk PHP

### 3. Code Quality

- ✅ Follow PSR-12 coding standards
- ✅ Use Laravel Pint untuk format code: `./vendor/bin/pint`
- ✅ Write tests untuk critical features
- ✅ Document API dengan Swagger annotations
- ✅ Use type hints dan return types
- ✅ Keep controllers thin, use services

### 4. Database

- ✅ Always use migrations untuk database changes
- ✅ Use seeders untuk sample data
- ✅ Backup database regularly
- ✅ Use soft deletes untuk data yang sensitif
- ✅ Index frequently queried columns

### 5. API Design

- ✅ Follow RESTful conventions
- ✅ Use proper HTTP status codes
- ✅ Versioning API (`/api/v1/`)
- ✅ Consistent response format
- ✅ Proper error messages

### 6. Development Workflow

```bash
# Before commit
./vendor/bin/pint           # Format code
php artisan test            # Run tests
php artisan l5-swagger:generate  # Update docs

# After pull
composer install
npm install
php artisan migrate
php artisan l5-swagger:generate
```

---

## 📞 Support & Contribution

### Repository
- **GitHub:** [RifqiF36/sso-backend](https://github.com/RifqiF36/sso-backend)

### Documentation Files
- **Quick Start:** `README.md`
- **API Guide:** `SWAGGER_GUIDE.md`
- **Full Documentation:** `DOCUMENTATION.md` (this file)
- **Postman Collection:** `docs/sso_mitra.postman_collection.json`

### Useful Commands

```bash
# Development
composer dev              # Run all dev services
php artisan serve         # Run server only
php artisan queue:work    # Run queue worker
php artisan pail          # Monitor logs

# Testing
php artisan test          # Run tests
php artisan test --filter=ClassName

# Documentation
php artisan l5-swagger:generate   # Generate Swagger docs
php artisan route:list            # List all routes

# Database
php artisan migrate              # Run migrations
php artisan migrate:fresh --seed # Fresh database with seed
php artisan db:seed              # Run seeders only

# Cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Code Quality
./vendor/bin/pint        # Format code
```

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

**Last Updated:** November 27, 2025
