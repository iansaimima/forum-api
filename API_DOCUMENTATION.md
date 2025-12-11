# Forum API Documentation

API untuk Forum sederhana dengan fitur topics, comments, likes, dan user follows.

## Base URL
```
http://localhost:8000/api
```

## Authentication
API ini menggunakan Laravel Sanctum untuk autentikasi. Setelah login/register, gunakan token yang diberikan di header:
```
Authorization: Bearer {token}
```

---

## Authentication Endpoints

### 1. Register
**POST** `/auth/register`

**Request Body:**
```json
{
  "name": "John Doe",
  "username": "johndoe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response:**
```json
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "username": "johndoe",
      "email": "john@example.com",
      "created_at": "2025-12-11T00:00:00.000000Z",
      "updated_at": "2025-12-11T00:00:00.000000Z"
    },
    "access_token": "1|abc123..."
  }
}
```

---

### 2. Login
**POST** `/auth/login`

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
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "username": "johndoe",
      "email": "john@example.com"
    },
    "access_token": "2|xyz789..."
  }
}
```

---

### 3. Forgot Password
**POST** `/auth/forgot-password`

**Request Body:**
```json
{
  "email": "john@example.com"
}
```

---

### 4. Reset Password
**POST** `/auth/reset-password`

**Request Body:**
```json
{
  "token": "reset_token",
  "email": "john@example.com",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

---

### 5. Logout
**POST** `/auth/logout`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "message": "Logout successful"
}
```

---

## Topic Endpoints

### 1. Get All Topics
**GET** `/topics`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `page` (optional): Page number for pagination

**Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "title": "Laravel Best Practices",
        "body": "What are the best practices when using Laravel?",
        "user_id": 1,
        "topic_category_id": 1,
        "created_at": "2025-12-11T00:00:00.000000Z",
        "updated_at": "2025-12-11T00:00:00.000000Z",
        "user": {
          "id": 1,
          "name": "John Doe",
          "username": "johndoe",
          "email": "john@example.com"
        },
        "category": {
          "id": 1,
          "name": "Laravel"
        },
        "comments_count": 5,
        "likes_count": 10
      }
    ],
    "per_page": 20,
    "total": 50
  }
}
```

---

### 2. Create Topic
**POST** `/topics`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "title": "Laravel Best Practices",
  "body": "What are the best practices when using Laravel?",
  "category_name": "Laravel"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Topic created successfully",
  "data": {
    "id": 1,
    "title": "Laravel Best Practices",
    "body": "What are the best practices when using Laravel?",
    "user_id": 1,
    "topic_category_id": 1,
    "created_at": "2025-12-11T00:00:00.000000Z",
    "updated_at": "2025-12-11T00:00:00.000000Z",
    "user": {
      "id": 1,
      "name": "John Doe",
      "username": "johndoe",
      "email": "john@example.com"
    },
    "category": {
      "id": 1,
      "name": "Laravel"
    }
  }
}
```

---

### 3. Get Topic Detail
**GET** `/topics/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Laravel Best Practices",
    "body": "What are the best practices when using Laravel?",
    "user_id": 1,
    "topic_category_id": 1,
    "created_at": "2025-12-11T00:00:00.000000Z",
    "updated_at": "2025-12-11T00:00:00.000000Z",
    "user": {
      "id": 1,
      "name": "John Doe",
      "username": "johndoe",
      "email": "john@example.com"
    },
    "category": {
      "id": 1,
      "name": "Laravel"
    },
    "comments": [
      {
        "id": 1,
        "body": "Great question!",
        "user": {
          "id": 2,
          "name": "Jane Doe",
          "username": "janedoe"
        },
        "created_at": "2025-12-11T00:00:00.000000Z"
      }
    ],
    "comments_count": 5,
    "likes_count": 10
  }
}
```

---

### 4. Update Topic
**PUT** `/topics/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "title": "Updated Title",
  "body": "Updated body content",
  "category_name": "PHP"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Topic updated successfully",
  "data": {
    "id": 1,
    "title": "Updated Title",
    "body": "Updated body content",
    "user_id": 1,
    "topic_category_id": 2,
    "user": {
      "id": 1,
      "name": "John Doe"
    },
    "category": {
      "id": 2,
      "name": "PHP"
    }
  }
}
```

---

### 5. Delete Topic
**DELETE** `/topics/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "message": "Topic deleted successfully"
}
```

---

## Comment Endpoints

### 1. Get Topic Comments
**GET** `/topics/{topicId}/comments`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `page` (optional): Page number for pagination

**Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "topic_id": 1,
        "user_id": 2,
        "body": "Great question!",
        "created_at": "2025-12-11T00:00:00.000000Z",
        "updated_at": "2025-12-11T00:00:00.000000Z",
        "user": {
          "id": 2,
          "name": "Jane Doe",
          "username": "janedoe",
          "email": "jane@example.com"
        }
      }
    ],
    "per_page": 20,
    "total": 5
  }
}
```

---

### 2. Create Comment
**POST** `/topics/{topicId}/comments`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "body": "This is a great topic!"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Comment created successfully",
  "data": {
    "id": 1,
    "topic_id": 1,
    "user_id": 2,
    "body": "This is a great topic!",
    "created_at": "2025-12-11T00:00:00.000000Z",
    "updated_at": "2025-12-11T00:00:00.000000Z",
    "user": {
      "id": 2,
      "name": "Jane Doe",
      "username": "janedoe"
    }
  }
}
```

---

### 3. Update Comment
**PUT** `/topics/{topicId}/comments/{commentId}`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "body": "Updated comment text"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Comment updated successfully",
  "data": {
    "id": 1,
    "topic_id": 1,
    "user_id": 2,
    "body": "Updated comment text",
    "created_at": "2025-12-11T00:00:00.000000Z",
    "updated_at": "2025-12-11T00:00:00.000000Z",
    "user": {
      "id": 2,
      "name": "Jane Doe",
      "username": "janedoe"
    }
  }
}
```

---

### 4. Delete Comment
**DELETE** `/topics/{topicId}/comments/{commentId}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "message": "Comment deleted successfully"
}
```

---

## Like Endpoints

### 1. Toggle Like/Unlike Topic
**POST** `/topics/{topicId}/like`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (Like):**
```json
{
  "success": true,
  "message": "Topic liked successfully",
  "data": {
    "liked": true,
    "likes_count": 11
  }
}
```

**Response (Unlike):**
```json
{
  "success": true,
  "message": "Topic unliked successfully",
  "data": {
    "liked": false,
    "likes_count": 10
  }
}
```

---

### 2. Get Users Who Liked Topic
**GET** `/topics/{topicId}/likes`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 2,
        "name": "Jane Doe",
        "username": "janedoe",
        "email": "jane@example.com"
      },
      {
        "id": 3,
        "name": "Bob Smith",
        "username": "bobsmith",
        "email": "bob@example.com"
      }
    ],
    "per_page": 20,
    "total": 10
  }
}
```

---

## User Endpoints

### 1. Search Users
**GET** `/users/search`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `query` (required): Search term (username, email, or name)
- `page` (optional): Page number for pagination

**Example:**
```
GET /users/search?query=john
```

**Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "name": "John Doe",
        "username": "johndoe",
        "email": "john@example.com",
        "created_at": "2025-12-11T00:00:00.000000Z"
      }
    ],
    "per_page": 20,
    "total": 1
  }
}
```

---

### 2. Get User Profile
**GET** `/users/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "username": "johndoe",
    "email": "john@example.com",
    "created_at": "2025-12-11T00:00:00.000000Z",
    "updated_at": "2025-12-11T00:00:00.000000Z",
    "topics_count": 15,
    "followers_count": 20,
    "following_count": 10,
    "is_following": false
  }
}
```

---

### 3. Follow User
**POST** `/users/{id}/follow`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "message": "User followed successfully"
}
```

---

### 4. Unfollow User
**DELETE** `/users/{id}/follow`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "message": "User unfollowed successfully"
}
```

---

### 5. Get User Followers
**GET** `/users/{id}/followers`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `page` (optional): Page number for pagination

**Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 2,
        "name": "Jane Doe",
        "username": "janedoe",
        "email": "jane@example.com"
      }
    ],
    "per_page": 20,
    "total": 20
  }
}
```

---

### 6. Get User Following
**GET** `/users/{id}/following`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `page` (optional): Page number for pagination

**Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 3,
        "name": "Bob Smith",
        "username": "bobsmith",
        "email": "bob@example.com"
      }
    ],
    "per_page": 20,
    "total": 10
  }
}
```

---

## Error Responses

### Validation Error (422)
```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "title": ["The title field is required."],
    "body": ["The body field is required."]
  }
}
```

### Unauthorized (401)
```json
{
  "message": "Unauthenticated."
}
```

### Forbidden (403)
```json
{
  "success": false,
  "message": "Unauthorized to update this topic"
}
```

### Not Found (404)
```json
{
  "success": false,
  "message": "Topic not found"
}
```

---

## Notes

1. Semua endpoint yang memerlukan autentikasi harus menyertakan token Bearer di header
2. Pagination default adalah 20 items per page
3. Hanya owner yang bisa update/delete topic dan comment mereka sendiri
4. Category akan otomatis dibuat jika belum ada saat membuat topic
5. Like menggunakan toggle, jadi endpoint yang sama untuk like dan unlike
6. User tidak bisa follow diri sendiri
7. Search users mengecualikan user yang sedang login
