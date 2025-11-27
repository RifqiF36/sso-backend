# Quick Reference - Swagger Documentation

## ✅ Yang Sudah Dibuat

### 1. File Swagger Annotations
- ✅ `app/Swagger/OpenApi.php` - Main OpenAPI configuration (sudah ada, updated dengan tag Staff)
- ✅ `app/Swagger/AuthPaths.php` - Auth endpoints (sudah ada, ditambah updateProfile & changePassword)
- ✅ `app/Swagger/TenantPaths.php` - Tenant endpoints (sudah ada)
- ✅ `app/Swagger/IamPaths.php` - IAM endpoints (sudah ada)
- ✅ `app/Swagger/AppPaths.php` - App Picker endpoints (sudah ada)
- ✅ `app/Swagger/StaffPaths.php` - **BARU** Staff user management endpoints

### 2. Dokumentasi Generated
- ✅ `storage/api-docs/api-docs.json` - OpenAPI 3.0 specification (auto-generated)
- ✅ Swagger UI tersedia di: `http://localhost:9000/api/documentation`

### 3. File Panduan
- ✅ `SWAGGER_GUIDE.md` - **BARU** Panduan lengkap penggunaan Swagger
- ✅ `README.md` - **UPDATED** Ditambahkan section API Documentation

## 🎯 Endpoint yang Terdokumentasi

### Auth (11 endpoints)
1. POST `/api/v1/auth/login`
2. POST `/api/v1/auth/logout`
3. GET `/api/v1/auth/me`
4. GET `/api/v1/auth/roles`
5. POST `/api/v1/auth/refresh`
6. PUT `/api/v1/auth/profile` ⭐ NEW
7. POST `/api/v1/auth/change-password` ⭐ NEW
8. GET `/api/v1/auth/sso/redirect`
9. GET `/api/v1/auth/sso/callback`
10. GET `/api/v1/auth/sso/authorize`
11. POST `/api/v1/auth/sso/token`
12. GET `/api/v1/auth/sso/userinfo`

### Tenant (2 endpoints)
1. GET `/api/v1/tenants`
2. POST `/api/v1/tenants/{tenant}/switch`

### IAM (5 endpoints)
1. GET `/api/v1/iam/roles`
2. GET `/api/v1/iam/users/{id}/roles`
3. POST `/api/v1/iam/assign-role`
4. POST `/api/v1/iam/revoke-role`
5. GET `/api/v1/iam/audit-logs`

### Staff (1 endpoint)
1. POST `/api/v1/staff/users` ⭐ NEW

### App Picker (1 endpoint)
1. GET `/api/v1/apps`

**Total: 20 endpoints terdokumentasi** 📊

## 🚀 Cara Menggunakan

### 1. Generate Dokumentasi
```bash
php artisan l5-swagger:generate
```

### 2. Akses Swagger UI
Buka browser: `http://localhost:9000/api/documentation`

### 3. Testing API
1. Login untuk mendapat token:
   ```
   POST /api/v1/auth/login
   Body: {"email":"admin_kota@sso","password":"AdminKota@123"}
   ```

2. Klik tombol **Authorize** di Swagger UI

3. Masukkan: `Bearer <your-token>`

4. Test endpoint yang lain dengan tombol **Try it out**

## 📝 Fitur Swagger yang Tersedia

- ✅ Interactive API testing (Try it out)
- ✅ Request/Response examples
- ✅ Schema definitions
- ✅ Bearer authentication support
- ✅ OAuth2 flow documentation
- ✅ Download OpenAPI spec (JSON/YAML)
- ✅ Organized by tags (Auth, SSO, Tenant, IAM, Staff, App Picker)

## 🔧 Konfigurasi

### Environment Variables (.env)
```env
L5_SWAGGER_CONST_HOST=http://127.0.0.1:9000
L5_SWAGGER_GENERATE_ALWAYS=false  # set true untuk auto-generate di development
```

### Config File
`config/l5-swagger.php` - Semua konfigurasi Swagger

## 📚 Resources

- Swagger UI: http://localhost:9000/api/documentation
- OpenAPI Spec: http://localhost:9000/docs/api-docs.json
- Panduan Detail: [SWAGGER_GUIDE.md](SWAGGER_GUIDE.md)
- Postman Collection: `docs/sso_mitra.postman_collection.json`

---
**Generated:** November 27, 2025
**OpenAPI Version:** 3.0.0
**Framework:** Laravel 11 + L5-Swagger
