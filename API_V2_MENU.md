# API V2 Auth - Menu System

## Endpoint: GET /api/v2/auth/me

Endpoint ini mengembalikan data user yang sedang login beserta menu yang sesuai dengan role mereka.

## Response Format

```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "staff"
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

## Menu Mapping by Role

| Role | Applications |
|------|-------------|
| admin_kota | SIPRIMA, SIMANTIC, SINDRA |
| kepala_dinas | SIMANTIC |
| admin_dinas | SIPRIMA, SIMANTIC, SINDRA |
| kepala_bidang | SIMANTIC, SINDRA |
| kepala_seksi | SIPRIMA, SIMANTIC, SINDRA |
| auditor | SIMANTIC, SIPRIMA |
| teknisi | SINDRA |
| staff | SIPRIMA, SIMANTIC, SINDRA |

## Application Details

### SIPRIMA (Asset Management)
- Key: `asset`
- Icon: `siprima.png`
- Description: Aset Management System

### SIMANTIC (Change Management)
- Key: `change`
- Icon: `simantic.png`
- Description: Change & Configuration Management

### SINDRA (Service Desk)
- Key: `maintenance`
- Icon: `sindra.png`
- Description: Service Desk Management

## Testing

### 1. Login first
```bash
curl -X POST http://localhost:8000/api/v2/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "password": "password"
  }'
```

### 2. Get user profile with menu
```bash
curl -X GET http://localhost:8000/api/v2/auth/me \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## Configuration

Menu configuration can be modified in:
- `/config/menu.php` - Role to menu mapping
- `/config/role_apps.php` - Application URLs and details

## Notes

- Menu URLs are automatically selected based on user role
- Each role gets different URLs for the same application
- Logo/icon files should be placed in the public directory
- URLs can be configured via environment variables for different environments
