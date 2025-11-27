# Panduan Swagger API Documentation

## 🚀 Akses Swagger UI

Setelah menjalankan aplikasi Laravel, Swagger UI dapat diakses melalui:

```
http://localhost:9000/api/documentation
```

atau sesuai dengan URL aplikasi Anda.

## 📋 Struktur Dokumentasi API

Dokumentasi API SSO Mitra mencakup endpoint-endpoint berikut:

### 1. **Auth** - Autentikasi Lokal
- `POST /api/v1/auth/login` - Login user dan dapatkan Sanctum token
- `POST /api/v1/auth/logout` - Logout dan revoke token
- `GET /api/v1/auth/me` - Dapatkan profil user yang sedang login
- `GET /api/v1/auth/roles` - Dapatkan daftar role user
- `POST /api/v1/auth/refresh` - Informasi refresh token
- `PUT /api/v1/auth/profile` - Update profil user
- `POST /api/v1/auth/change-password` - Ubah password user

### 2. **SSO** - OAuth2 Authorization Code Grant Flow
- `GET /api/v1/auth/sso/authorize` - Dapatkan authorization code untuk aplikasi klien
- `POST /api/v1/auth/sso/token` - Tukar authorization code dengan access token
- `GET /api/v1/auth/sso/userinfo` - Dapatkan informasi user (untuk callback client)
- `GET /api/v1/auth/sso/redirect` - Redirect ke IdP eksternal (legacy)
- `GET /api/v1/auth/sso/callback` - Callback dari IdP eksternal (legacy)

### 3. **Tenant** - Manajemen Tenant/OPD
- `GET /api/v1/tenants` - Daftar tenant milik user
- `POST /api/v1/tenants/{tenant}/switch` - Switch tenant aktif

### 4. **IAM** - Identity & Access Management
- `GET /api/v1/iam/roles` - Daftar role berdasarkan modul
- `GET /api/v1/iam/users/{id}/roles` - Daftar role user per tenant & module
- `POST /api/v1/iam/assign-role` - Assign role ke user
- `POST /api/v1/iam/revoke-role` - Revoke role dari user
- `GET /api/v1/iam/audit-logs` - Audit log IAM terbaru

### 5. **App Picker** - Daftar Aplikasi
- `GET /api/v1/apps` - Daftar aplikasi yang bisa diakses user berdasarkan role

### 6. **Staff** - Manajemen User
- `POST /api/v1/staff/users` - Membuat akun user baru (hanya untuk role staff)

## 🔐 Autentikasi

Sebagian besar endpoint memerlukan autentikasi menggunakan **Bearer Token**. 

### Cara Menggunakan Bearer Token di Swagger UI:

1. Klik tombol **"Authorize"** di bagian atas Swagger UI
2. Masukkan token dalam format: `Bearer <your-token-here>`
3. Klik **"Authorize"** kemudian **"Close"**

### Mendapatkan Token:

Gunakan endpoint `POST /api/v1/auth/login` dengan kredensial:

```json
{
  "email": "admin_kota@sso",
  "password": "AdminKota@123"
}
```

Response akan berisi token yang dapat digunakan untuk autentikasi:

```json
{
  "token": "1|admin-kota-demo-token",
  "user": {...}
}
```

## 🔄 Generate Ulang Dokumentasi

Jika Anda melakukan perubahan pada anotasi Swagger di file-file berikut:
- `app/Swagger/OpenApi.php`
- `app/Swagger/AuthPaths.php`
- `app/Swagger/TenantPaths.php`
- `app/Swagger/IamPaths.php`
- `app/Swagger/AppPaths.php`
- `app/Swagger/StaffPaths.php`

Jalankan command berikut untuk regenerate dokumentasi:

```bash
php artisan l5-swagger:generate
```

## ⚙️ Konfigurasi

File konfigurasi Swagger terletak di:
```
config/l5-swagger.php
```

Untuk mengubah base URL server, edit file `.env`:
```env
L5_SWAGGER_CONST_HOST=http://127.0.0.1:9000
```

Untuk auto-generate dokumentasi setiap request (development mode):
```env
L5_SWAGGER_GENERATE_ALWAYS=true
```

## 📝 OAuth2 Flow untuk SSO

### Authorization Code Grant Flow:

1. **Authorize** - Client aplikasi redirect user ke:
   ```
   GET /api/v1/auth/sso/authorize?client_id=siprima-app&redirect_uri=...&state=...
   ```
   
2. **Token Exchange** - Setelah mendapat authorization code, tukar dengan access token:
   ```
   POST /api/v1/auth/sso/token
   {
     "code": "...",
     "client_id": "siprima-app",
     "client_secret": "supersecret123",
     "grant_type": "authorization_code",
     "redirect_uri": "..."
   }
   ```

3. **Get User Info** - Gunakan access token untuk dapatkan informasi user:
   ```
   GET /api/v1/auth/sso/userinfo
   Authorization: Bearer <access-token>
   ```

## 🎯 Tips Penggunaan

1. **Try It Out** - Klik tombol "Try it out" pada setiap endpoint untuk mencoba langsung
2. **Response Examples** - Lihat contoh response di bagian "Responses" setiap endpoint
3. **Model Schemas** - Swagger UI menampilkan struktur data yang diharapkan untuk request body
4. **Download Spec** - Download OpenAPI spec dalam format JSON/YAML dari `/docs/api-docs.json`

## 📚 Dokumentasi Tambahan

- OpenAPI Specification: https://swagger.io/specification/
- L5-Swagger Documentation: https://github.com/DarkaOnLine/L5-Swagger
- Laravel Sanctum: https://laravel.com/docs/sanctum

## 🐛 Troubleshooting

### Swagger UI tidak muncul?
- Pastikan server Laravel sudah berjalan
- Periksa route `/api/documentation` sudah terdaftar: `php artisan route:list | grep documentation`
- Clear cache: `php artisan cache:clear && php artisan config:clear`

### Dokumentasi tidak terupdate?
- Generate ulang: `php artisan l5-swagger:generate`
- Atau set `L5_SWAGGER_GENERATE_ALWAYS=true` di `.env`

### Authorization tidak bekerja?
- Pastikan format token benar: `Bearer <token>`
- Pastikan token masih valid (belum expired atau revoked)
- Gunakan token dari response `/api/v1/auth/login`
