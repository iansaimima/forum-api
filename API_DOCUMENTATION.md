# Forum API Documentation

API untuk Forum sederhana dengan fitur topics, comments, likes, dan user follows.

## Base URL
```
https://api.forum.gutsylab.com/api
```

## Authentication
API ini menggunakan Laravel Sanctum untuk autentikasi. Setelah login/register, gunakan token yang diberikan di header:
```
Authorization: Bearer {your_token}
```

## Total Endpoints
| Kategori   | Jumlah |
|------------|--------|
| 🔐 Auth    | 5      |
| 📝 Topics  | 8      |
| 💬 Comments| 4      |
| ❤️ Likes   | 2      |
| 👥 Users   | 12     |
| **Total**  | **31** |

---

## 🔐 Authentication Endpoints

### 1. Register
**POST** `/auth/register`

Register a new user account.

**Request Body:**
```json
{
  "name": "John Doe",
  "username": "johndoe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "phone": "+6281234567890",
  "address": "Jakarta, Indonesia"
}
```

> `phone` dan `address` bersifat opsional.

**Response (201) - Success:**
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
      "emailVerifiedAt": null,
      "profilePhoto": null,
      "phone": null,
      "address": null,
      "createdAtFormatted": "26 Mar 2026, 10:00",
      "createdAtAgo": "just now",
      "updatedAtFormatted": "26 Mar 2026, 10:00",
      "updatedAtAgo": "just now"
    },
    "accessToken": "1|abc123...",
    "tokenType": "Bearer"
  }
}
```

**Response (200) - Validation Failed:**
```json
{
  "success": false,
  "message": "Registration validation failed",
  "errors": {
    "email": ["The email has already been taken."],
    "username": ["The username has already been taken."]
  }
}
```

---

### 2. Login
**POST** `/auth/login`

Login to existing account.

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response (200) - Success:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "username": "johndoe",
      "email": "john@example.com",
      "emailVerifiedAt": null,
      "profilePhoto": "profile_photos/johndoe.jpg",
      "createdAtFormatted": "01 Jan 2026, 00:00",
      "createdAtAgo": "2 months ago",
      "updatedAtFormatted": "26 Mar 2026, 10:00",
      "updatedAtAgo": "just now"
    },
    "accessToken": "2|xyz789...",
    "tokenType": "Bearer"
  }
}
```

**Response (200) - User Not Found:**
```json
{
  "success": false,
  "message": "User not found with this email."
}
```

**Response (200) - Wrong Password:**
```json
{
  "success": false,
  "message": "Incorrect password."
}
```

**Response (200) - Validation Failed:**
```json
{
  "success": false,
  "message": "Login validation failed",
  "errors": {
    "email": ["Email is required"]
  }
}
```

---

### 3. Logout
**POST** `/auth/logout` 🔒

Logout from current session. Requires authentication.

**Response (200):**
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

---

### 4. Forgot Password
**POST** `/auth/forgot-password`

Request password reset link to be sent to user's email.

**Request Body:**
```json
{
  "email": "john@example.com"
}
```

**Response (200) - Success:**
```json
{
  "success": true,
  "message": "Password reset link sent to your email"
}
```

**Response (200) - Validation Failed:**
```json
{
  "success": false,
  "message": "Forgot password validation failed",
  "errors": {
    "email": ["No user found with this email address"]
  }
}
```

> - User akan menerima email berisi link reset password.
> - Link mengandung token yang memiliki waktu kedaluwarsa.
> - Email harus terdaftar di sistem.

---

### 5. Reset Password
**POST** `/auth/reset-password`

Reset user password using the token from email.

**Request Body:**
```json
{
  "token": "reset_token_from_email",
  "email": "john@example.com",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

**Validation Rules:**
```
token: required
email: required, must be valid email
password: required, minimum 8 characters, must match confirmation
password_confirmation: required, must match password
```

**Response (200) - Success:**
```json
{
  "success": true,
  "message": "Password reset successfully"
}
```

**Response (422) - Validation Error:**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["This password reset token is invalid."]
  }
}
```

---

## 📝 Topics Endpoints

### 1. Get Feed Topics
**GET** `/topics` 🔒

Get topics from users you follow and your own topics with pagination.

**Query Parameters:**
- `page` (optional): Page number for pagination

> - Hanya menampilkan topics dari user yang kamu follow.
> - Termasuk topics milik kamu sendiri.
> - Gunakan `/topics/trending` untuk melihat semua trending topics.

**Response (200):**
```json
{
  "success": true,
  "data": {
    "currentPage": 1,
    "data": [
      {
        "id": 1,
        "title": "Laravel Best Practices",
        "body": "What are the best practices...",
        "userId": 1,
        "topicCategoryId": 1,
        "createdAtFormatted": "26 Mar 2026, 10:00",
        "createdAtAgo": "2 hours ago",
        "updatedAtFormatted": "26 Mar 2026, 12:00",
        "updatedAtAgo": "just now",
        "commentsCount": 5,
        "likesCount": 10,
        "isLike": false,
        "user": {
          "id": 1,
          "name": "John Doe",
          "username": "johndoe",
          "email": "john@example.com",
          "emailVerifiedAt": null,
          "profilePhoto": "profile_photos/johndoe.jpg",
          "createdAtFormatted": "01 Jan 2026, 00:00",
          "createdAtAgo": "2 months ago",
          "updatedAtFormatted": "26 Mar 2026, 10:00",
          "updatedAtAgo": "just now",
          "profilePhotoUrl": "https://domain.com/storage/profile_photos/johndoe.jpg"
        },
        "category": {
          "id": 1,
          "name": "Laravel"
        },
        "comments": [...],
        "likes": [...]
      }
    ],
    "perPage": 20,
    "total": 50,
    "lastPage": 3
  }
}
```

---

### 2. Get Trending Topics
**GET** `/topics/trending` 🔒

Get top 10 trending topics with most comments.

> - Mengembalikan maksimal 10 topics.
> - Diurutkan berdasarkan jumlah komentar (descending).
> - Menyertakan semua relasi topic (user, category, likes).

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Laravel Best Practices",
      "body": "What are the best practices...",
      "userId": 1,
      "topicCategoryId": 1,
      "createdAtFormatted": "26 Mar 2026, 10:00",
      "createdAtAgo": "2 hours ago",
      "updatedAtFormatted": "26 Mar 2026, 12:00",
      "updatedAtAgo": "just now",
      "commentsCount": 25,
      "likesCount": 10,
      "isLiked": false,
      "user": {
        "id": 1,
        "name": "John Doe",
        "username": "johndoe",
        "email": "john@example.com",
        "emailVerifiedAt": null,
        "profilePhoto": "profile_photos/johndoe.jpg",
        "createdAtFormatted": "01 Jan 2026, 00:00",
        "createdAtAgo": "2 months ago",
        "updatedAtFormatted": "26 Mar 2026, 10:00",
        "updatedAtAgo": "just now",
        "profilePhotoUrl": "https://domain.com/storage/profile_photos/johndoe.jpg"
      },
      "category": {
        "id": 1,
        "name": "Laravel"
      },
      "likes": [...]
    }
  ]
}
```

---

### 3. Get All Categories
**GET** `/topics/categories` 🔒

Get all distinct categories that have at least one topic, sorted alphabetically.

> - Hanya mengembalikan category yang memiliki minimal 1 topic.
> - Diurutkan alphabetically berdasarkan nama.
> - Menyertakan `topics_count` untuk setiap category.

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "JavaScript",
      "topics_count": 8
    },
    {
      "id": 2,
      "name": "Laravel",
      "topics_count": 12
    },
    {
      "id": 3,
      "name": "PHP",
      "topics_count": 5
    }
  ]
}
```

---

### 4. Get Topics by Category
**GET** `/topics/category/{categoryName}` 🔒

Get paginated topics filtered by a specific category name.

**URL Parameters:**
- `categoryName` (required): Nama category (contoh: Laravel, PHP, JavaScript)

**Query Parameters:**
- `page` (optional): Page number for pagination

**Example:**
```
GET /topics/category/Laravel?page=1
```

> - Category name bersifat case-sensitive.
> - Mengembalikan 20 topics per halaman, diurutkan terbaru.

**Response (200) - Success:**
```json
{
  "success": true,
  "category": "Laravel",
  "data": {
    "currentPage": 1,
    "data": [
      {
        "id": 1,
        "title": "Laravel Best Practices",
        "body": "What are the best practices...",
        "userId": 1,
        "topicCategoryId": 1,
        "createdAtFormatted": "26 Mar 2026, 10:00",
        "createdAtAgo": "2 hours ago",
        "updatedAtFormatted": "26 Mar 2026, 12:00",
        "updatedAtAgo": "just now",
        "commentsCount": 5,
        "likesCount": 10,
        "isLiked": false,
        "user": {
          "id": 1,
          "name": "John Doe",
          "username": "johndoe",
          "email": "john@example.com",
          "profilePhotoUrl": "https://domain.com/storage/profile_photos/johndoe.jpg"
        },
        "category": {
          "id": 1,
          "name": "Laravel"
        },
        "comments": [...],
        "likes": [...]
      }
    ],
    "perPage": 20,
    "total": 30,
    "lastPage": 2
  }
}
```

**Response (404) - Category Not Found:**
```json
{
  "success": false,
  "message": "Category not found"
}
```

---

### 5. Create Topic
**POST** `/topics` 🔒

Create a new topic. Category will be created automatically if not exists.

**Request Body:**
```json
{
  "title": "Laravel Best Practices",
  "body": "What are the best practices when using Laravel?",
  "category_name": "Laravel"
}
```

**Response (201) - Success:**
```json
{
  "success": true,
  "message": "Topic created successfully",
  "data": {
    "id": 1,
    "title": "Laravel Best Practices",
    "body": "What are the best practices when using Laravel?",
    "userId": 1,
    "topicCategoryId": 1,
    "createdAt": "2026-03-26T10:00:00.000000Z",
    "updatedAt": "2026-03-26T10:00:00.000000Z",
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

**Response (200) - Validation Error:**
```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "title": ["The title field is required."],
    "categoryName": ["The category name field is required."]
  }
}
```

---

### 6. Get Topic Detail
**GET** `/topics/{id}` 🔒

Get detailed information about a specific topic.

**Response (200) - Success:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Laravel Best Practices",
    "body": "What are the best practices...",
    "userId": 1,
    "topicCategoryId": 1,
    "createdAtFormatted": "26 Mar 2026, 10:00",
    "createdAtAgo": "2 hours ago",
    "updatedAtFormatted": "26 Mar 2026, 12:00",
    "updatedAtAgo": "just now",
    "commentsCount": 5,
    "likesCount": 10,
    "isLiked": false,
    "user": {
      "id": 1,
      "name": "John Doe",
      "username": "johndoe",
      "email": "john@example.com",
      "emailVerifiedAt": null,
      "profilePhoto": "profile_photos/johndoe.jpg",
      "createdAtFormatted": "01 Jan 2026, 00:00",
      "createdAtAgo": "2 months ago",
      "updatedAtFormatted": "26 Mar 2026, 10:00",
      "updatedAtAgo": "just now",
      "profilePhotoUrl": "https://domain.com/storage/profile_photos/johndoe.jpg"
    },
    "category": {
      "id": 1,
      "name": "Laravel"
    },
    "comments": [
      {
        "id": 1,
        "topicId": 1,
        "userId": 2,
        "body": "Great question!",
        "createdAtFormatted": "26 Mar 2026, 10:30",
        "createdAtAgo": "30 minutes ago",
        "updatedAtFormatted": "26 Mar 2026, 10:30",
        "updatedAtAgo": "30 minutes ago",
        "user": {
          "id": 2,
          "name": "Jane Doe",
          "username": "janedoe",
          "email": "jane@example.com",
          "profilePhotoUrl": "https://domain.com/storage/profile_photos/janedoe.jpg"
        }
      }
    ],
    "likes": [...]
  }
}
```

**Response (404) - Not Found:**
```json
{
  "success": false,
  "message": "Topic not found"
}
```

---

### 7. Update Topic
**PUT** `/topics/{id}` 🔒

Update a topic. Only the owner can update their topic. All fields are optional.

**Request Body:**
```json
{
  "title": "Updated Title",
  "body": "Updated body content",
  "category_name": "PHP"
}
```

**Response (200) - Success:**
```json
{
  "success": true,
  "message": "Topic updated successfully",
  "data": {
    "id": 1,
    "title": "Updated Title",
    "body": "Updated body content",
    "userId": 1,
    "topicCategoryId": 2,
    "createdAt": "2026-03-26T10:00:00.000000Z",
    "updatedAt": "2026-03-26T12:00:00.000000Z",
    "user": {
      "id": 1,
      "name": "John Doe",
      "username": "johndoe",
      "email": "john@example.com"
    },
    "category": {
      "id": 2,
      "name": "PHP"
    }
  }
}
```

**Response (403) - Unauthorized:**
```json
{
  "success": false,
  "message": "Unauthorized to update this topic"
}
```

**Response (404) - Not Found:**
```json
{
  "success": false,
  "message": "Topic not found"
}
```

---

### 8. Delete Topic
**DELETE** `/topics/{id}` 🔒

Delete a topic. Only the owner can delete their topic.

**Response (200) - Success:**
```json
{
  "success": true,
  "message": "Topic deleted successfully"
}
```

**Response (403) - Unauthorized:**
```json
{
  "success": false,
  "message": "Unauthorized to delete this topic"
}
```

**Response (404) - Not Found:**
```json
{
  "success": false,
  "message": "Topic not found"
}
```

---

## 💬 Comments Endpoints

### 1. Get Topic Comments
**GET** `/topics/{topicId}/comments` 🔒

Get all comments for a specific topic with pagination.

**Response (200) - Success:**
```json
{
  "success": true,
  "data": {
    "currentPage": 1,
    "data": [
      {
        "id": 1,
        "topicId": 1,
        "userId": 2,
        "body": "Great question!",
        "createdAtAgo": "30 minutes ago",
        "createdAtFormatted": "26 Mar 2026, 10:30",
        "updatedAtAgo": "30 minutes ago",
        "updatedAtFormatted": "26 Mar 2026, 10:30",
        "user": {
          "id": 2,
          "name": "Jane Doe",
          "email": "jane@example.com",
          "username": "janedoe",
          "profilePhotoUrl": "https://domain.com/storage/profile_photos/janedoe.jpg"
        }
      }
    ],
    "perPage": 20,
    "total": 10,
    "lastPage": 1
  }
}
```

**Response (404) - Topic Not Found:**
```json
{
  "success": false,
  "message": "Topic not found"
}
```

---

### 2. Create Comment
**POST** `/topics/{topicId}/comments` 🔒

Create a new comment on a topic.

**Request Body:**
```json
{
  "body": "This is a great topic!"
}
```

**Response (201) - Success:**
```json
{
  "success": true,
  "message": "Comment created successfully",
  "data": {
    "id": 1,
    "topicId": 1,
    "userId": 2,
    "body": "This is a great topic!",
    "createdAt": "2026-03-26T10:00:00.000000Z",
    "updatedAt": "2026-03-26T10:00:00.000000Z",
    "user": {
      "id": 2,
      "name": "Jane Doe",
      "username": "janedoe",
      "email": "jane@example.com",
      "profilePhotoUrl": "https://domain.com/storage/profile_photos/janedoe.jpg"
    }
  }
}
```

**Response (404) - Topic Not Found:**
```json
{
  "success": false,
  "message": "Topic not found"
}
```

---

### 3. Update Comment
**PUT** `/topics/{topicId}/comments/{commentId}` 🔒

Update a comment. Only the owner can update their comment.

**Request Body:**
```json
{
  "body": "Updated comment text"
}
```

**Response (200) - Success:**
```json
{
  "success": true,
  "message": "Comment updated successfully",
  "data": {
    "id": 1,
    "topicId": 1,
    "userId": 2,
    "body": "Updated comment text",
    "createdAt": "2026-03-26T10:00:00.000000Z",
    "updatedAt": "2026-03-26T12:00:00.000000Z",
    "user": {
      "id": 2,
      "name": "Jane Doe",
      "username": "janedoe",
      "email": "jane@example.com",
      "profilePhotoUrl": "https://domain.com/storage/profile_photos/janedoe.jpg"
    }
  }
}
```

**Response (403) - Unauthorized:**
```json
{
  "success": false,
  "message": "Unauthorized to update this comment"
}
```

**Response (404) - Not Found:**
```json
{
  "success": false,
  "message": "Comment not found"
}
```

---

### 4. Delete Comment
**DELETE** `/topics/{topicId}/comments/{commentId}` 🔒

Delete a comment. Only the owner can delete their comment.

**Response (200) - Success:**
```json
{
  "success": true,
  "message": "Comment deleted successfully"
}
```

**Response (403) - Unauthorized:**
```json
{
  "success": false,
  "message": "Unauthorized to delete this comment"
}
```

**Response (404) - Not Found:**
```json
{
  "success": false,
  "message": "Comment not found"
}
```

---

## ❤️ Likes Endpoints

### 1. Toggle Like / Unlike
**POST** `/topics/{topicId}/like` 🔒

Toggle like/unlike on a topic (same endpoint for both).

**Response (200) - Liked:**
```json
{
  "success": true,
  "message": "Topic liked successfully",
  "data": {
    "liked": true,
    "likesCount": 11
  }
}
```

**Response (200) - Unliked:**
```json
{
  "success": true,
  "message": "Topic unliked successfully",
  "data": {
    "liked": false,
    "likesCount": 10
  }
}
```

**Response (404) - Topic Not Found:**
```json
{
  "success": false,
  "message": "Topic not found"
}
```

---

### 2. Get Users Who Liked a Topic
**GET** `/topics/{topicId}/likes` 🔒

Get list of users who liked a topic with pagination.

**Response (200) - Success:**
```json
{
  "success": true,
  "data": {
    "currentPage": 1,
    "data": [
      {
        "id": 2,
        "name": "Jane Doe",
        "username": "janedoe",
        "email": "jane@example.com",
        "createdAtFormatted": "01 Feb 2026, 14:30",
        "createdAtAgo": "1 month ago",
        "updatedAtFormatted": "10 Mar 2026, 09:15",
        "updatedAtAgo": "3 days ago",
        "profilePhotoUrl": "https://domain.com/storage/profile_photos/janedoe.jpg"
      }
    ],
    "perPage": 20,
    "total": 11,
    "lastPage": 1
  }
}
```

**Response (404) - Topic Not Found:**
```json
{
  "success": false,
  "message": "Topic not found"
}
```

---

## 👥 Users Endpoints

### 1. Search Users
**GET** `/users/search` 🔒

Search users by username, email, or name.

**Query Parameters:**
- `query` (required): Search term
- `page` (optional): Page number

**Example:**
```
GET /users/search?query=john&page=1
```

> - Mencari di field username, email, dan name.
> - Hasil tidak menyertakan user yang sedang login.
> - `isFollow` menunjukkan apakah kamu sudah follow user tersebut.

**Response (200) - Success:**
```json
{
  "success": true,
  "data": {
    "currentPage": 1,
    "data": [
      {
        "id": 2,
        "name": "John Smith",
        "username": "johnsmith",
        "email": "johnsmith@example.com",
        "createdAtFormatted": "15 Feb 2026, 10:00",
        "createdAtAgo": "1 month ago",
        "updatedAtFormatted": "12 Mar 2026, 14:20",
        "updatedAtAgo": "1 day ago",
        "isFollow": false,
        "profilePhotoUrl": "https://domain.com/storage/profile_photos/johnsmith.jpg"
      }
    ],
    "perPage": 20,
    "total": 5,
    "lastPage": 1
  }
}
```

**Response (422) - Validation Error:**
```json
{
  "success": false,
  "message": "Query parameter is required"
}
```

---

### 2. Get User Profile
**GET** `/users/{id}` 🔒

Get user profile with stats (topics count, followers, following).

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "username": "johndoe",
    "email": "john@example.com",
    "emailVerifiedAt": null,
    "profilePhoto": "profile_photos/johndoe.jpg",
    "createdAtFormatted": "01 Jan 2026, 00:00",
    "createdAtAgo": "2 months ago",
    "updatedAtFormatted": "10 Mar 2026, 15:30",
    "updatedAtAgo": "3 days ago",
    "topicsCount": 15,
    "followersCount": 20,
    "followingCount": 10,
    "isFollow": false,
    "isYou": false,
    "profilePhotoUrl": "https://domain.com/storage/profile_photos/johndoe.jpg"
  }
}
```

> - `isFollow`: menunjukkan apakah authenticated user mengikuti profil ini.
> - `isYou`: `true` jika melihat profil sendiri.

**Response (404):**
```json
{
  "success": false,
  "message": "User not found"
}
```

---

### 3. Follow User
**POST** `/users/{id}/follow` 🔒

Follow a user.

**Response (200) - Success:**
```json
{
  "success": true,
  "message": "User followed successfully"
}
```

**Response (422) - Already Following:**
```json
{
  "success": false,
  "message": "You are already following this user"
}
```

**Response (422) - Cannot Follow Self:**
```json
{
  "success": false,
  "message": "You cannot follow yourself"
}
```

**Response (404) - User Not Found:**
```json
{
  "success": false,
  "message": "User not found"
}
```

---

### 4. Unfollow User
**DELETE** `/users/{id}/follow` 🔒

Unfollow a user.

**Response (200):**
```json
{
  "success": true,
  "message": "User unfollowed successfully"
}
```

**Response (422) - Not Following:**
```json
{
  "success": false,
  "message": "You are not following this user"
}
```

**Response (404) - User Not Found:**
```json
{
  "success": false,
  "message": "User not found"
}
```

---

### 5. Get User Followers
**GET** `/users/{id}/followers` 🔒

Get list of a user's followers with pagination.

**Query Parameters:**
- `page` (optional): Page number for pagination

> - Mengembalikan paginated list of followers (20 per page).
> - `isFollow` menunjukkan apakah kamu balik mengikuti mereka.

**Response (200):**
```json
{
  "success": true,
  "data": {
    "currentPage": 1,
    "data": [
      {
        "id": 2,
        "name": "Jane Doe",
        "username": "janedoe",
        "email": "jane@example.com",
        "createdAtFormatted": "01 Feb 2026, 14:30",
        "createdAtAgo": "1 month ago",
        "updatedAtFormatted": "10 Mar 2026, 09:15",
        "updatedAtAgo": "3 days ago",
        "isFollow": true,
        "profilePhotoUrl": "https://domain.com/storage/profile_photos/janedoe.jpg"
      }
    ],
    "perPage": 20,
    "total": 100,
    "lastPage": 5
  }
}
```

**Response (404):**
```json
{
  "success": false,
  "message": "User not found"
}
```

---

### 6. Get User Following
**GET** `/users/{id}/following` 🔒

Get list of users that this user is following with pagination.

**Query Parameters:**
- `page` (optional): Page number for pagination

> - `isFollow` menunjukkan apakah kamu juga mengikuti mereka.
> - Bisa diakses untuk user manapun, tidak terbatas following.

**Response (200):**
```json
{
  "success": true,
  "data": {
    "currentPage": 1,
    "data": [
      {
        "id": 3,
        "name": "Bob Smith",
        "username": "bobsmith",
        "email": "bob@example.com",
        "createdAtFormatted": "15 Jan 2026, 08:00",
        "createdAtAgo": "2 months ago",
        "updatedAtFormatted": "12 Mar 2026, 16:45",
        "updatedAtAgo": "1 day ago",
        "isFollow": false,
        "profilePhotoUrl": null
      }
    ],
    "perPage": 20,
    "total": 50,
    "lastPage": 3
  }
}
```

**Response (404):**
```json
{
  "success": false,
  "message": "User not found"
}
```

---

### 7. Get User Topics
**GET** `/users/{id}/topics` 🔒

Get all topics created by a specific user with pagination.

**Query Parameters:**
- `page` (optional): Page number for pagination

> - Menampilkan semua topics milik user yang dimaksud.
> - Bisa diakses untuk user manapun, tidak terbatas following.

**Response (200):**
```json
{
  "success": true,
  "data": {
    "currentPage": 1,
    "data": [
      {
        "id": 1,
        "title": "Laravel Best Practices",
        "body": "What are the best practices...",
        "userId": 1,
        "topicCategoryId": 1,
        "createdAtFormatted": "13 Mar 2026, 10:30",
        "createdAtAgo": "2 hours ago",
        "updatedAtFormatted": "13 Mar 2026, 10:30",
        "updatedAtAgo": "2 hours ago",
        "commentsCount": 5,
        "likesCount": 10,
        "isLike": false,
        "user": {
          "id": 1,
          "name": "John Doe",
          "username": "johndoe",
          "email": "john@example.com",
          "profilePhotoUrl": "https://domain.com/storage/profile_photos/johndoe.jpg"
        },
        "category": {
          "id": 1,
          "name": "Laravel"
        },
        "comments": [...],
        "likes": [...]
      }
    ],
    "perPage": 20,
    "total": 50,
    "lastPage": 3
  }
}
```

**Response (404):**
```json
{
  "success": false,
  "message": "User not found"
}
```

---

## 🖼️ Profile Endpoints (Authenticated User)

### 1. Get My Profile
**GET** `/users/profile` 🔒

Get the authenticated user's own profile with stats.

> `isYou` akan selalu `true` untuk endpoint ini. Response identik dengan `GET /users/{id}`.

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "username": "johndoe",
    "email": "john@example.com",
    "emailVerifiedAt": null,
    "profilePhoto": "profile_photos/johndoe.jpg",
    "createdAtFormatted": "01 Jan 2026, 00:00",
    "createdAtAgo": "2 months ago",
    "updatedAtFormatted": "27 Mar 2026, 10:00",
    "updatedAtAgo": "just now",
    "topicsCount": 15,
    "followersCount": 20,
    "followingCount": 10,
    "isFollow": false,
    "isYou": true,
    "profilePhotoUrl": "https://domain.com/storage/profile_photos/johndoe.jpg"
  }
}
```

---

### 2. Get My Followers
**GET** `/users/profile-followers` 🔒

Get the authenticated user's own followers list with pagination.

> Response identik dengan `GET /users/{id}/followers` untuk authenticated user.

**Response (200):** _(sama seperti GET /users/{id}/followers)_

---

### 3. Get My Following
**GET** `/users/profile-following` 🔒

Get the list of users that the authenticated user is following with pagination.

> Response identik dengan `GET /users/{id}/following` untuk authenticated user.

**Response (200):** _(sama seperti GET /users/{id}/following)_

---

### 4. Get My Topics
**GET** `/users/profile-topics` 🔒

Get all topics created by the authenticated user with pagination.

> Response identik dengan `GET /users/{id}/topics` untuk authenticated user.

**Response (200):** _(sama seperti GET /users/{id}/topics)_

---

### 5. Upload Profile Photo
**POST** `/users/profile-photo` 🔒

Upload or replace the authenticated user's profile photo.

**Request Body (multipart/form-data):**
```
photo: (file) jpeg/png/jpg/gif, max 2MB
```

> - Format yang diterima: jpeg, png, jpg, gif.
> - Ukuran maksimal: 2MB.
> - Foto sebelumnya otomatis dihapus saat upload foto baru.
> - Filename diset menjadi `{username}.{extension}`.

**Response (200) - Success:**
```json
{
  "success": true,
  "message": "Profile photo uploaded successfully",
  "data": {
    "profilePhoto": "profile_photos/johndoe.jpg",
    "profilePhotoUrl": "https://domain.com/storage/profile_photos/johndoe.jpg"
  }
}
```

**Response (422) - Validation Error:**
```json
{
  "message": "The photo field is required.",
  "errors": {
    "photo": ["The photo must be an image.", "The photo must not be greater than 2048 kilobytes."]
  }
}
```

---

## ⚠️ Error Responses

### Unauthenticated (401)
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

---

## 📌 Important Notes

1. Semua endpoint yang memerlukan autentikasi (🔒) harus menyertakan header `Authorization: Bearer {token}`.
2. Default pagination adalah **20 items per page**.
3. Hanya owner yang bisa update/delete topic dan comment milik mereka sendiri.
4. Category otomatis dibuat jika belum ada saat membuat topic.
5. Like menggunakan **toggle mechanism** — endpoint yang sama untuk like dan unlike.
6. User **tidak bisa follow diri sendiri**.
7. Search users **mengecualikan** user yang sedang login.
8. Semua response key menggunakan **camelCase** (contoh: `topicsCount`, `profilePhotoUrl`, `createdAtAgo`).
9. `profilePhotoUrl` adalah full URL ke foto profil; `profilePhoto` adalah raw storage path.
10. `GET /topics` hanya menampilkan topics dari user yang kamu follow dan topics milikmu sendiri.
11. `GET /topics/trending` menampilkan top 10 topics dengan komentar terbanyak dari semua user.
12. `GET /topics/categories` hanya menampilkan category yang memiliki minimal 1 topic.
13. `GET /topics/category/{categoryName}` — `categoryName` bersifat **case-sensitive**.
14. `GET /users/{id}/topics` menampilkan semua topics milik user manapun (tidak terbatas following).
