# API V2 Authentication - Swagger Documentation

## Overview
API V2 Authentication menyediakan endpoint untuk autentikasi user dengan fitur:
- Registration dengan email verification (OTP)
- Login dengan email & password
- Email verification menggunakan 6-digit OTP
- Password reset dengan OTP
- Role-based menu system
- User profile dengan menu akses aplikasi

---

## Base URL
```
http://localhost:8000/api/v2/auth
```

---

## Endpoints

### 1. Register
**POST** `/api/v2/auth/register`

Mendaftarkan user baru dan mengirim OTP untuk verifikasi email.

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john.doe@example.com",
  "password": "Password123",
  "password_confirmation": "Password123",
  "nip": "199001012020011001",  // optional
  "phone": "081234567890"        // optional
}
```

**Response 201:**
```json
{
  "success": true,
  "message": "Registration successful. Please check your email for verification code.",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john.doe@example.com",
      "nip": "199001012020011001",
      "phone": "081234567890"
    }
  }
}
```

---

### 2. Verify Email
**POST** `/api/v2/auth/verify`

Verifikasi email menggunakan kode OTP yang dikirim saat registrasi.

**Request Body:**
```json
{
  "email": "john.doe@example.com",
  "otp": "123456"
}
```

**Response 200:**
```json
{
  "success": true,
  "message": "Email verified successfully",
  "data": {
    "token": "1|abc123def456",
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john.doe@example.com",
      "email_verified_at": "2025-11-28T10:30:00.000000Z"
    }
  }
}
```

---

### 3. Login
**POST** `/api/v2/auth/login`

Login user dengan email dan password.

**Request Body:**
```json
{
  "email": "john.doe@example.com",
  "password": "Password123"
}
```

**Response 200:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "token": "2|xyz789abc123",
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john.doe@example.com",
      "role": "staff"
    }
  }
}
```

**Error Responses:**
- **401** - Invalid credentials
- **403** - Email not verified

---

### 4. Get User Profile (Me)
**GET** `/api/v2/auth/me`

Mendapatkan profil user yang sedang login beserta menu aplikasi berdasarkan role.

**Headers:**
```
Authorization: Bearer {token}
```

**Response 200:**
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john.doe@example.com",
      "role": "staff",
      "nip": "199001012020011001"
    },
    "menu": [
      {
        "name": "Asset Management",
        "url": "https://dinas-siprima.vercel.app/",
        "logo": "siprima.png",
        "description": "Aset Management System"
      },
      {
        "name": "Change Management",
        "url": "http://127.0.0.1:8000",
        "logo": "simantic.png",
        "description": "Change & Configuration Management"
      },
      {
        "name": "Service Desk",
        "url": "http://127.0.0.1:8000",
        "logo": "sindra.png",
        "description": "Service Desk Management"
      }
    ]
  }
}
```

---

### 5. Get User By ID
**GET** `/api/v2/auth/user/{id}`

Mendapatkan informasi user berdasarkan ID.

**Headers:**
```
Authorization: Bearer {token}
```

**Response 200:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john.doe@example.com",
    "role": "staff"
  }
}
```

---

### 6. Logout
**POST** `/api/v2/auth/logout`

Logout dan revoke semua token user.

**Headers:**
```
Authorization: Bearer {token}
```

**Response 200:**
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

---

### 7. Reset Password (Request OTP)
**POST** `/api/v2/auth/reset-password`

Meminta kode OTP untuk reset password yang akan dikirim ke email.

**Request Body:**
```json
{
  "email": "john.doe@example.com"
}
```

**Response 200:**
```json
{
  "success": true,
  "message": "Password reset code has been sent to your email"
}
```

---

### 8. Confirm Reset Password
**POST** `/api/v2/auth/confirm-reset-password`

Konfirmasi reset password dengan OTP dan password baru.

**Request Body:**
```json
{
  "email": "john.doe@example.com",
  "otp": "123456",
  "password": "NewPassword123",
  "password_confirmation": "NewPassword123"
}
```

**Response 200:**
```json
{
  "success": true,
  "message": "Password reset successfully"
}
```

---

## Menu System

Menu yang ditampilkan pada endpoint `/me` disesuaikan dengan role user:

| Role | Applications |
|------|-------------|
| **admin_kota** | SIPRIMA, SIMANTIC, SINDRA |
| **kepala_dinas** | SIMANTIC |
| **admin_dinas** | SIPRIMA, SIMANTIC, SINDRA |
| **kepala_bidang** | SIMANTIC, SINDRA |
| **kepala_seksi** | SIPRIMA, SIMANTIC, SINDRA |
| **auditor** | SIMANTIC, SIPRIMA |
| **teknisi** | SINDRA |
| **staff** | SIPRIMA, SIMANTIC, SINDRA |

### Aplikasi

- **SIPRIMA** - Asset Management (`siprima.png`)
- **SIMANTIC** - Change Management (`simantic.png`)
- **SINDRA** - Service Desk (`sindra.png`)

---

## Authentication

Semua endpoint yang memerlukan autentikasi harus menyertakan header:
```
Authorization: Bearer {token}
```

Token didapatkan dari endpoint:
- `/verify` - Setelah verifikasi email
- `/login` - Setelah login

---

## Error Responses

### 400 - Bad Request
```json
{
  "success": false,
  "message": "Invalid or expired OTP"
}
```

### 401 - Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### 403 - Forbidden
```json
{
  "success": false,
  "message": "Email not verified"
}
```

### 404 - Not Found
```json
{
  "success": false,
  "message": "User not found"
}
```

### 422 - Validation Error
```json
{
  "message": "The email has already been taken.",
  "errors": {
    "email": [
      "The email has already been taken."
    ]
  }
}
```

---

## Testing dengan cURL

### 1. Register
```bash
curl -X POST http://localhost:8000/api/v2/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john.doe@example.com",
    "password": "Password123",
    "password_confirmation": "Password123"
  }'
```

### 2. Verify Email
```bash
curl -X POST http://localhost:8000/api/v2/auth/verify \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john.doe@example.com",
    "otp": "123456"
  }'
```

### 3. Login
```bash
curl -X POST http://localhost:8000/api/v2/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john.doe@example.com",
    "password": "Password123"
  }'
```

### 4. Get Profile with Menu
```bash
curl -X GET http://localhost:8000/api/v2/auth/me \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### 5. Logout
```bash
curl -X POST http://localhost:8000/api/v2/auth/logout \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## Notes

- OTP berlaku selama **10 menit**
- Password minimal **8 karakter**
- Email harus **unik**
- Token akan di-revoke saat logout atau reset password
- Menu URL dapat dikonfigurasi via environment variables di `.env`

---

## Swagger UI

Akses Swagger UI di:
```
http://localhost:8000/api/documentation
```
