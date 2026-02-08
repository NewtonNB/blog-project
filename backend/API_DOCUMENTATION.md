# Blog API Documentation

This is a complete Laravel backend API for a blog website designed to work with a React frontend.

## Features

- User authentication using Laravel Sanctum
- CRUD operations for blog posts and categories
- Proper relationships between Users, Posts, and Categories
- Input validation and error handling
- JSON responses with appropriate HTTP status codes
- Password encryption using Hash::make
- CORS configuration for React frontend

## Models & Relationships

### User Model
- Has many posts
- Fields: name, email, password, bio, avatar
- Uses Laravel Sanctum for API authentication

### Post Model
- Belongs to User (author)
- Belongs to Category
- Fields: title, slug, content, excerpt, featured_image, status, published_at
- Status can be 'draft' or 'published'
- Automatic slug generation from title

### Category Model
- Has many posts
- Fields: name, slug, description
- Automatic slug generation from name

## API Endpoints

### Authentication Endpoints

#### Register User
```
POST /api/auth/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "bio": "Full-stack developer"
}
```

#### Login User
```
POST /api/auth/login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "password123"
}
```

#### Logout User (Protected)
```
POST /api/auth/logout
Authorization: Bearer {token}
```

#### Get User Profile (Protected)
```
GET /api/auth/profile
Authorization: Bearer {token}
```

### Post Endpoints

#### Get All Posts (Public)
```
GET /api/posts?status=published&category=technology&search=laravel&per_page=10
```

#### Get Single Post (Public)
```
GET /api/posts/{slug}
```

#### Create Post (Protected)
```
POST /api/posts
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "My First Blog Post",
    "content": "This is the content of my blog post...",
    "excerpt": "Short description of the post",
    "featured_image": "https://example.com/image.jpg",
    "status": "published",
    "category_id": 1,
    "published_at": "2024-01-28T10:00:00Z"
}
```

#### Update Post (Protected)
```
PUT /api/posts/{slug}
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "Updated Blog Post Title",
    "content": "Updated content...",
    "status": "published"
}
```

#### Delete Post (Protected)
```
DELETE /api/posts/{slug}
Authorization: Bearer {token}
```

### Category Endpoints

#### Get All Categories (Public)
```
GET /api/categories
```

#### Get Single Category with Posts (Public)
```
GET /api/categories/{slug}
```

#### Create Category (Protected)
```
POST /api/categories
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Technology",
    "description": "Posts about technology and programming"
}
```

#### Update Category (Protected)
```
PUT /api/categories/{slug}
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Web Development",
    "description": "Updated description"
}
```

#### Delete Category (Protected)
```
DELETE /api/categories/{slug}
Authorization: Bearer {token}
```

## Response Format

All API responses follow this format:

### Success Response
```json
{
    "success": true,
    "message": "Operation successful",
    "data": {
        // Response data here
    }
}
```

### Error Response
```json
{
    "success": false,
    "message": "Error message",
    "errors": {
        // Validation errors (if any)
    }
}
```

## HTTP Status Codes

- `200` - OK (successful GET, PUT, DELETE)
- `201` - Created (successful POST)
- `400` - Bad Request (general client error)
- `401` - Unauthorized (authentication required)
- `403` - Forbidden (insufficient permissions)
- `404` - Not Found (resource doesn't exist)
- `422` - Unprocessable Entity (validation errors)
- `500` - Internal Server Error (server error)

## Authentication

This API uses Laravel Sanctum for authentication. After login/register, you'll receive a bearer token that should be included in the Authorization header for protected routes:

```
Authorization: Bearer {your-token-here}
```

## CORS Configuration

The API is configured to accept requests from:
- `http://localhost:3000` (React development server)
- `http://127.0.0.1:3000`

## Database Setup

1. Copy `.env.example` to `.env`
2. Set your database configuration
3. Run migrations: `php artisan migrate`
4. Seed sample data: `php artisan db:seed`

## Installation Steps

1. Install Laravel Sanctum: `composer require laravel/sanctum`
2. Publish Sanctum configuration: `php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"`
3. Run migrations: `php artisan migrate`
4. Add Sanctum middleware to API routes in `bootstrap/app.php`

## Sample Data

The seeder creates:
- 5 sample users (including john@example.com and jane@example.com)
- 5 categories (Technology, Web Development, Mobile Development, Programming, Design)
- Multiple sample blog posts

Default password for seeded users: `password`

## Frontend Integration

For React frontend integration:

1. Install axios or fetch for API calls
2. Store the authentication token in localStorage or context
3. Include the token in API request headers
4. Handle authentication state in your React app

Example React API call:
```javascript
const response = await fetch('http://localhost:8000/api/posts', {
    headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
    }
});
```