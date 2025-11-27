# 🚀 Quick Start - Testing API dengan Swagger

## Step 1: Jalankan Server

```bash
php artisan serve
```

Server akan berjalan di: `http://127.0.0.1:9000`

## Step 2: Buka Swagger UI

Buka browser dan akses:
```
http://127.0.0.1:9000/api/documentation
```

Anda akan melihat dokumentasi API interaktif dengan semua endpoint yang tersedia.

## Step 3: Login untuk Mendapat Token

1. Cari endpoint **POST /api/v1/auth/login** di section **Auth**
2. Klik **Try it out**
3. Isi request body:
   ```json
   {
     "email": "admin_kota@sso",
     "password": "AdminKota@123"
   }
   ```
4. Klik **Execute**
5. Copy token dari response (contoh: `1|admin-kota-demo-token`)

## Step 4: Authorize di Swagger

1. Klik tombol **Authorize** 🔓 di bagian atas halaman
2. Di dialog yang muncul, masukkan:
   ```
   Bearer 1|admin-kota-demo-token
   ```
   *(ganti dengan token dari step 3)*
3. Klik **Authorize**
4. Klik **Close**

Sekarang Anda sudah terautentikasi! 🎉

## Step 5: Test Endpoint Lainnya

Coba test endpoint yang memerlukan autentikasi, misalnya:

### Get User Profile
1. Cari **GET /api/v1/auth/me**
2. Klik **Try it out**
3. Klik **Execute**
4. Lihat response berisi data user Anda

### Switch Tenant
1. Cari **POST /api/v1/tenants/{tenant}/switch**
2. Klik **Try it out**
3. Isi parameter `tenant` dengan ID tenant (misalnya: `1`)
4. Klik **Execute**

### Get Apps
1. Cari **GET /api/v1/apps**
2. Klik **Try it out**
3. Klik **Execute**
4. Lihat daftar aplikasi yang bisa diakses

## 💡 Tips

- **Response Schema**: Klik tab "Schema" untuk melihat struktur response
- **Example Values**: Swagger otomatis mengisi form dengan contoh nilai
- **Clear Authorization**: Klik 🔓 lagi untuk logout/clear token
- **Collapse/Expand**: Klik nama tag untuk collapse/expand section

## 🔄 Testing OAuth2 Flow (untuk Developer Client App)

### 1. Get Authorization Code
```
GET /api/v1/auth/sso/authorize
  ?client_id=siprima-app
  &redirect_uri=http://127.0.0.1:8000/api/v1/auth/sso/callback
  &state=demo123
  &response_type=code
```

### 2. Exchange Code for Token
```
POST /api/v1/auth/sso/token
{
  "code": "1e1c5e7c1c6d40b0bdb4b7f777281234",
  "client_id": "siprima-app",
  "client_secret": "supersecret123",
  "grant_type": "authorization_code",
  "redirect_uri": "http://127.0.0.1:8000/api/v1/auth/sso/callback"
}
```

### 3. Get User Info
```
GET /api/v1/auth/sso/userinfo
Authorization: Bearer <access-token-from-step-2>
```

## 🎯 Common Use Cases

### Create New User (Staff Only)
```
POST /api/v1/staff/users
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "SecurePass123",
  "role": "admin_opd",
  "nip": "199001012015011001",
  "gender": "Laki-laki",
  "unit_kerja": "Bidang TIK",
  "asal_dinas": "Diskominfo"
}
```

### Assign Role to User
```
POST /api/v1/iam/assign-role
{
  "user_id": 1,
  "role": "admin_kota",
  "tenant_id": 1,
  "module": "asset_risk"
}
```

### Update Profile
```
PUT /api/v1/auth/profile
{
  "name": "John Doe Updated",
  "nip": "199001012015011001",
  "gender": "Laki-laki",
  "unit_kerja": "Bidang TIK",
  "asal_dinas": "Diskominfo"
}
```

### Change Password
```
POST /api/v1/auth/change-password
{
  "current_password": "OldPassword123",
  "new_password": "NewPassword123",
  "new_password_confirmation": "NewPassword123"
}
```

## 📥 Import ke Postman

Jika lebih suka menggunakan Postman:

1. Download OpenAPI spec:
   ```
   http://127.0.0.1:9000/docs/api-docs.json
   ```

2. Di Postman:
   - Klik **Import**
   - Pilih **Link** atau **File**
   - Paste URL atau upload file JSON
   - Klik **Import**

Atau gunakan collection yang sudah disediakan:
```
docs/sso_mitra.postman_collection.json
```

## 🆘 Troubleshooting

### Swagger UI tidak muncul?
- Pastikan server Laravel sudah berjalan
- Check route: `php artisan route:list | grep documentation`
- Clear cache: `php artisan cache:clear`

### Token tidak bekerja?
- Pastikan format: `Bearer <token>` (ada spasi setelah Bearer)
- Pastikan token masih valid (tidak expired/revoked)
- Login ulang untuk dapat token baru

### Dokumentasi tidak update?
```bash
php artisan l5-swagger:generate
```

Atau set di `.env`:
```env
L5_SWAGGER_GENERATE_ALWAYS=true
```

---

**Happy Testing! 🎉**

Untuk panduan lengkap, lihat: [SWAGGER_GUIDE.md](SWAGGER_GUIDE.md)
