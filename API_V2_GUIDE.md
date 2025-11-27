# API V2 Documentation

## Overview

API V2 adalah versi simplified dari authentication endpoints dengan response yang lebih minimal dan fokus pada core functionality.

## Base URL

```
http://localhost:9000/api/v2
```

## Endpoints

### Authentication

#### 1. Login
**POST** `/auth/login`

Login dan dapatkan authentication token.

**Request Body:**
```json
{
  "email": "admin_kota@sso",
  "password": "AdminKota@123"
}
```

**Response (200 OK):**
```json
{
  "token": "1|randomtokenstringhere"
}
```

**Response (401 Unauthorized):**
```json
{
  "message": "Invalid credentials"
}
```

---

#### 2. Get Current User
**GET** `/auth/me`

Dapatkan informasi user yang sedang login.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
  "id": 1,
  "name": "Admin Kota",
  "email": "admin_kota@sso",
  "email_verified_at": null,
  "created_at": "2025-01-01T00:00:00.000000Z",
  "updated_at": "2025-01-01T00:00:00.000000Z"
}
```

**Response (401 Unauthorized):**
```json
{
  "message": "Unauthenticated."
}
```

---

#### 3. Logout
**POST** `/auth/logout`

Logout dan revoke semua token user.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
  "message": "Logged out successfully"
}
```

**Response (401 Unauthorized):**
```json
{
  "message": "Unauthenticated."
}
```

---

## Swagger Documentation

Dokumentasi interaktif tersedia di:
```
http://localhost:9000/api/documentation
```

Cari section **"Auth V2"** untuk mencoba endpoints V2.

---

## Perbedaan dengan V1

| Feature | V1 | V2 |
|---------|----|----|
| Login Response | Detailed (user, roles, tenants, apps) | Minimal (hanya token) |
| Me Response | Detailed dengan relasi | Basic user data |
| Complexity | High | Low |
| Use Case | Full SSO dengan IAM | Simple authentication |

---

## Example Usage dengan cURL

### Login
```bash
curl -X POST http://localhost:9000/api/v2/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin_kota@sso","password":"AdminKota@123"}'
```

### Get Current User
```bash
curl -X GET http://localhost:9000/api/v2/auth/me \
  -H "Authorization: Bearer 1|your-token-here"
```

### Logout
```bash
curl -X POST http://localhost:9000/api/v2/auth/logout \
  -H "Authorization: Bearer 1|your-token-here"
```

---

## Example Usage dengan JavaScript (Axios)

```javascript
// Login
const loginResponse = await axios.post('http://localhost:9000/api/v2/auth/login', {
  email: 'admin_kota@sso',
  password: 'AdminKota@123'
});

const token = loginResponse.data.token;

// Get Current User
const userResponse = await axios.get('http://localhost:9000/api/v2/auth/me', {
  headers: {
    'Authorization': `Bearer ${token}`
  }
});

console.log(userResponse.data);

// Logout
await axios.post('http://localhost:9000/api/v2/auth/logout', {}, {
  headers: {
    'Authorization': `Bearer ${token}`
  }
});
```

---

## Security Notes

1. **HTTPS Required in Production:** Selalu gunakan HTTPS untuk melindungi token dari man-in-the-middle attacks
2. **Token Storage:** Simpan token di secure storage (httpOnly cookies atau secure localStorage)
3. **Token Expiry:** Token tidak memiliki expiry default, implement refresh mechanism jika diperlukan
4. **Rate Limiting:** Implement rate limiting untuk prevent brute force attacks pada login endpoint

---

Last Updated: November 28, 2025
