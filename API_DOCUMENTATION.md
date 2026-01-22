# JWT API Documentation

## Base URL
```
http://localhost:8000/api
```

## Authentication Endpoints

### 1. Register
สร้างผู้ใช้ใหม่และได้รับ JWT token

**Endpoint:** `POST /api/register`

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response:**
```json
{
    "status": "success",
    "message": "User registered successfully",
    "user": {
        "name": "John Doe",
        "email": "john@example.com",
        "updated_at": "2026-01-20T10:00:00.000000Z",
        "created_at": "2026-01-20T10:00:00.000000Z",
        "id": 1
    },
    "authorization": {
        "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
        "type": "bearer"
    }
}
```

### 2. Login
เข้าสู่ระบบและได้รับ JWT token

**Endpoint:** `POST /api/login`

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response:**
```json
{
    "status": "success",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
    },
    "authorization": {
        "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
        "type": "bearer"
    }
}
```

### 3. Get User Profile
ดึงข้อมูลผู้ใช้ปัจจุบัน (ต้องมี token)

**Endpoint:** `GET /api/me`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "status": "success",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
    }
}
```

### 4. Refresh Token
ต่ออายุ token

**Endpoint:** `POST /api/refresh`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "status": "success",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
    },
    "authorization": {
        "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
        "type": "bearer"
    }
}
```

### 5. Logout
ออกจากระบบ (invalidate token)

**Endpoint:** `POST /api/logout`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "status": "success",
    "message": "Successfully logged out"
}
```

## Protected Data Endpoints

### 6. Get All Users
ดึงรายการผู้ใช้ทั้งหมด

**Endpoint:** `GET /api/users`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "created_at": "2026-01-20T10:00:00.000000Z"
        }
    ]
}
```

### 7. Get Specific User
ดึงข้อมูลผู้ใช้คนใดคนหนึ่ง

**Endpoint:** `GET /api/users/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "status": "success",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2026-01-20T10:00:00.000000Z"
    }
}
```

### 8. Update User
อัพเดทข้อมูลผู้ใช้ (เฉพาะของตนเอง)

**Endpoint:** `PUT /api/users/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
    "name": "John Updated",
    "email": "john.updated@example.com"
}
```

**Response:**
```json
{
    "status": "success",
    "message": "User updated successfully",
    "data": {
        "id": 1,
        "name": "John Updated",
        "email": "john.updated@example.com"
    }
}
```

### 9. Delete User
ลบผู้ใช้ (เฉพาะของตนเอง)

**Endpoint:** `DELETE /api/users/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "status": "success",
    "message": "User deleted successfully"
}
```

## การใช้งานกับ Frontend

### ตัวอย่างการเรียก API ด้วย JavaScript (Fetch)

```javascript
// 1. Login
const login = async () => {
    const response = await fetch('http://localhost:8000/api/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            email: 'john@example.com',
            password: 'password123'
        })
    });
    
    const data = await response.json();
    
    // เก็บ token ใน localStorage
    if (data.status === 'success') {
        localStorage.setItem('token', data.authorization.token);
    }
    
    return data;
};

// 2. ดึงข้อมูลผู้ใช้ทั้งหมด (ต้องมี token)
const getUsers = async () => {
    const token = localStorage.getItem('token');
    
    const response = await fetch('http://localhost:8000/api/users', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`
        }
    });
    
    const data = await response.json();
    return data;
};

// 3. ดึงข้อมูลผู้ใช้ปัจจุบัน
const getProfile = async () => {
    const token = localStorage.getItem('token');
    
    const response = await fetch('http://localhost:8000/api/me', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`
        }
    });
    
    const data = await response.json();
    return data;
};

// 4. Logout
const logout = async () => {
    const token = localStorage.getItem('token');
    
    const response = await fetch('http://localhost:8000/api/logout', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`
        }
    });
    
    // ลบ token จาก localStorage
    localStorage.removeItem('token');
    
    return await response.json();
};
```

### ตัวอย่างการใช้งานกับ Axios

```javascript
import axios from 'axios';

// ตั้งค่า base URL
const api = axios.create({
    baseURL: 'http://localhost:8000/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
});

// Interceptor สำหรับเพิ่ม token ทุก request
api.interceptors.request.use(
    config => {
        const token = localStorage.getItem('token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    error => Promise.reject(error)
);

// Interceptor สำหรับจัดการ error
api.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 401) {
            // Token หมดอายุหรือไม่ถูกต้อง
            localStorage.removeItem('token');
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);

// ใช้งาน
const login = async (email, password) => {
    const { data } = await api.post('/login', { email, password });
    if (data.status === 'success') {
        localStorage.setItem('token', data.authorization.token);
    }
    return data;
};

const getUsers = async () => {
    const { data } = await api.get('/users');
    return data;
};
```

## Error Responses

### 401 Unauthorized
```json
{
    "status": "error",
    "message": "Invalid credentials"
}
```

### 422 Validation Error
```json
{
    "status": "error",
    "message": "Validation errors",
    "errors": {
        "email": ["The email field is required."]
    }
}
```

### 404 Not Found
```json
{
    "status": "error",
    "message": "User not found"
}
```

## หมายเหตุ
- Token มีอายุ 60 นาที (สามารถแก้ไขได้ที่ `config/jwt.php`)
- ควรใช้ HTTPS ในการส่ง token บน production
- Token ควรเก็บไว้ใน localStorage หรือ httpOnly cookie
- ควรมี refresh token mechanism สำหรับ production
